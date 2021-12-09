<?php

declare(strict_types=1);

use Nelexa\RequestDtoBundle\Tests\App;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return static function (RoutingConfigurator $routes): void {
    $routes->add('registration', '/registration')->controller(App\Controller\RegistrationController::class);
    $routes->add('bad-registration', '/register-exception')->controller(App\Controller\BadRegistrationController::class);
    $routes->add('search', '/search')->controller(App\Controller\SearchController::class);

    $routes->add('search-nullable-errors', '/search-nullable-errors')->controller(App\Controller\SearchNullableErrorsController::class);
    $routes->add('search-exception', '/search-exception')->controller(App\Controller\SearchExceptionController::class);
    $routes->add('user-token', '/user-token')->controller(App\Controller\UserTokenController::class);
    $routes->add('user-token-exception', '/user-token-exception')->controller(App\Controller\UserTokenExceptionController::class);
    $routes->add('errors', '/errors')->controller(App\Controller\ErrorsController::class);
    $routes->add('multiple-objects', '/multiple/objects')
        ->controller(App\Controller\MultipleObjectsController::class)
        ->methods(['POST'])
    ;
    $routes->add('limit', '/limit')->controller(App\Controller\LimitController::class);
    $routes->add('construct-request', '/construct/request')
        ->controller(App\Controller\ConstructRequestController::class)
        ->methods(['POST'])
    ;
    $routes->add('construct-request-exception', '/construct/request/exception')
        ->controller(App\Controller\ConstructRequestExceptionController::class)
        ->methods(['POST'])
    ;
    $routes->add('unsupport', '/unsupport')
        ->controller(App\Controller\UnsupportController::class)
    ;
    $routes->add('upload-single-file', '/upload/single')
        ->controller(App\Controller\UploadSingleFileController::class)
        ->methods(['POST'])
    ;
    $routes->add('upload-nested-file', '/upload/nested')
        ->controller(App\Controller\UploadNestedFileController::class)
        ->methods(['POST'])
    ;
    $routes->add('update-files', '/files/update')
        ->controller(App\Controller\FilesUpdateController::class)
        ->methods(['POST'])
    ;

    $routes->add('root-body', '/root/body')
        ->controller(App\Controller\RootBodyController::class)
        ->methods(['POST'])
    ;
    $routes->add('root-form', '/root/form')
        ->controller(App\Controller\RootFormController::class)
        ->methods(['POST'])
    ;
};
