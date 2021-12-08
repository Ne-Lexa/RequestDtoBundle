<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $container): void {
    $container->services()
        ->load('Nelexa\RequestDtoBundle\Tests\App\Controller\\', '../../Controller')
        ->autoconfigure(true)
        ->autowire(true)
        ->tag('controller.service_arguments')
    ;
};
