<?php

declare(strict_types=1);

namespace Nelexa\RequestDtoBundle\Tests\App;

use Psr\Log\NullLogger;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\Kernel;

class TestingKernel extends Kernel
{
    public function registerBundles(): iterable
    {
        return [
            new \Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new \Nelexa\RequestDtoBundle\RequestDtoBundle(),
        ];
    }

    protected function build(ContainerBuilder $container): void
    {
        $container->register('logger', NullLogger::class);
        $container->loadFromExtension(
            'framework',
            [
                'secret' => '$ecret',
                'router' => [
                    'utf8' => true,
                    'resource' => __DIR__ . '/Resources/config/routes.php',
                ],
                'test' => true,
                'validation' => [
                    'enabled' => true,
                ],
            ]
        );
        $container->setParameter('locale', 'en');
    }

    /**
     * @throws \Exception
     */
    public function registerContainerConfiguration(LoaderInterface $loader): void
    {
        $loader->load(__DIR__ . '/Resources/config/services.php');
    }

    public function shutdown(): void
    {
        parent::shutdown();

        $cacheDirectory = $this->getCacheDir();
        $logDirectory = $this->getLogDir();

        $filesystem = new Filesystem();

        if ($filesystem->exists($cacheDirectory)) {
            $filesystem->remove($cacheDirectory);
        }

        if ($filesystem->exists($logDirectory)) {
            $filesystem->remove($logDirectory);
        }
    }
}
