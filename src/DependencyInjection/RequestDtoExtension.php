<?php

declare(strict_types=1);

namespace Nelexa\RequestDtoBundle\DependencyInjection;

use Nelexa\RequestDtoBundle\ArgumentResolver\ConstraintViolationListValueResolver;
use Nelexa\RequestDtoBundle\ArgumentResolver\RequestDtoValueResolver;
use Nelexa\RequestDtoBundle\EventListener\RequestDtoControllerArgumentListener;
use Nelexa\RequestDtoBundle\EventListener\RequestDtoExceptionListener;
use Nelexa\RequestDtoBundle\Normalizer\RequestDtoExceptionNormalizer;
use Nelexa\RequestDtoBundle\Transform\RequestDtoTransform;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\KernelEvents;

class RequestDtoExtension extends Extension
{
    /**
     * Loads a specific configuration.
     *
     * @throws \InvalidArgumentException|\Exception When provided tag is not defined in this extension
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $this->registerTransform($container);
        $this->registerArgumentResolvers($container);
        $this->registerEventListeners($container);
        $this->registerNormalizers($container);
    }

    private function registerTransform(ContainerBuilder $container): void
    {
        $definition = new Definition(
            RequestDtoTransform::class,
            [
                new Reference('serializer'),
            ]
        );
        $definition->setPublic(false);

        $container->setDefinition(RequestDtoTransform::class, $definition);
    }

    private function registerArgumentResolvers(ContainerBuilder $container): void
    {
        $this->registerArgumentResolver($container, RequestDtoValueResolver::class, 40);
        $this->registerArgumentResolver($container, ConstraintViolationListValueResolver::class, -40);
    }

    private function registerArgumentResolver(ContainerBuilder $container, string $className, int $priority = 0): void
    {
        $definition = new Definition(
            $className,
            [
                new Reference(RequestDtoTransform::class),
                new Reference('validator'),
            ]
        );
        $definition->setPublic(false);
        $definition->addTag(
            'controller.argument_value_resolver',
            [
                'priority' => $priority,
            ]
        );

        $container->setDefinition($className, $definition);
    }

    private function registerEventListeners(ContainerBuilder $container): void
    {
        $this->registerEventListenerControllerArguments($container);
        $this->registerExceptionEventListener($container);
    }

    private function registerEventListenerControllerArguments(ContainerBuilder $container): void
    {
        $definition = new Definition(RequestDtoControllerArgumentListener::class);
        $definition->setPublic(false);
        $definition->addTag(
            'kernel.event_listener',
            [
                'event' => KernelEvents::CONTROLLER_ARGUMENTS,
                'method' => 'onControllerArguments',
            ]
        );

        $container->setDefinition(RequestDtoControllerArgumentListener::class, $definition);
    }

    private function registerExceptionEventListener(ContainerBuilder $container): void
    {
        $definition = new Definition(
            RequestDtoExceptionListener::class,
            [
                new Reference('serializer'),
            ]
        );
        $definition->setPublic(false);
        $definition->addTag(
            'kernel.event_listener',
            [
                'event' => KernelEvents::EXCEPTION,
                'method' => 'onKernelException',
                'priority' => 30,
            ]
        );

        $container->setDefinition(RequestDtoExceptionListener::class, $definition);
    }

    private function registerNormalizers(ContainerBuilder $container): void
    {
        $definition = new Definition(
            RequestDtoExceptionNormalizer::class,
            [
                new Reference('serializer.normalizer.constraint_violation_list'),
                '%kernel.debug%',
            ]
        );
        $definition->setPublic(false);
        $definition->addTag(
            'serializer.normalizer',
            [
                'priority' => -885,
            ]
        );

        $container->setDefinition(RequestDtoExceptionNormalizer::class, $definition);
    }
}
