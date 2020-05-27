<?php

declare(strict_types=1);

namespace Nelexa\RequestDtoBundle\Examples\App;

use Nelexa\RequestDtoBundle\Examples\Dto\LimitQueryRequest;
use Nelexa\RequestDtoBundle\Examples\Dto\ObjectFromRequest;
use Nelexa\RequestDtoBundle\Examples\Dto\SearchQueryRequest;
use Nelexa\RequestDtoBundle\Examples\Dto\UserRegistrationRequest;
use Nelexa\RequestDtoBundle\Examples\Dto\UserTokenRequest;
use Nelexa\RequestDtoBundle\RequestDtoBundle;
use Psr\Log\NullLogger;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Routing\RouteCollectionBuilder;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class AppKernel extends Kernel
{
    use MicroKernelTrait;

    public function registerBundles()
    {
        return [
            new FrameworkBundle(),
            new RequestDtoBundle(),
        ];
    }

    protected function configureContainer(ContainerBuilder $c, LoaderInterface $loader): void
    {
        $c->register('logger', NullLogger::class);
        $c->loadFromExtension(
            'framework',
            [
                'secret' => '$ecret',
                'router' => ['utf8' => true],
                'validation' => [
                    'enabled' => true,
                ],
            ]
        );
        $c->setParameter('locale', 'en');
    }

    protected function configureRoutes(RouteCollectionBuilder $routes): void
    {
        $routes->add('/register', 'kernel::registration');
        $routes->add('/register-exception', 'kernel::registrationException');
        $routes->add('/search', 'kernel::search');
        $routes->add('/search-nullable-errors', 'kernel::searchWithNullableErrors');
        $routes->add('/search-exception', 'kernel::searchException');
        $routes->add('/user-token', 'kernel::userTokenAction');
        $routes->add('/user-token-exception', 'kernel::userTokenExceptionAction');
        $routes->add('/errors', 'kernel::constraintViolationListAction');
        $routes->add('/multiple/objects', 'kernel::multipleObjects')
            ->setMethods(['POST']);
        $routes->add('/limit', 'kernel::limitAction');
        $routes->add('/construct/request', 'kernel::constructRequestAction')
            ->setMethods(['POST']);
        $routes->add('/construct/request/exception', 'kernel::constructRequestExceptionAction')
            ->setMethods(['POST']);
    }

    /**
     * Returns a JsonResponse that uses the serializer component if enabled, or json_encode.
     *
     * @param mixed $data
     */
    private function serializeResponse(
        Request $request,
        $data,
        int $status = 200,
        array $headers = [],
        array $context = []
    ): Response {
        $format = $request->getPreferredFormat('json');

        if (!\in_array($format, ['json', 'xml'], true)) {
            $format = 'json';
        }

        $serializeData = $this->container->get('serializer')->serialize(
            $data,
            $format,
            $context
        );

        $headers += [
            'Content-Type' => $request->getMimeType($format),
        ];

        return new Response($serializeData, $status, $headers);
    }

    public function registration(
        Request $request,
        UserRegistrationRequest $userRegistration,
        ConstraintViolationListInterface $errors
    ): Response {
        $data = [
            'dto' => $userRegistration,
            'errors' => $errors,
        ];

        return $this->serializeResponse($request, $data);
    }

    public function registrationException(
        Request $request,
        UserRegistrationRequest $userRegistration
    ): Response {
        return $this->serializeResponse($request, $userRegistration);
    }

    public function search(
        Request $request,
        SearchQueryRequest $searchRequest,
        ConstraintViolationListInterface $errors
    ): Response {
        $data = [
            'dto' => $searchRequest,
            'errors' => $errors,
        ];

        return $this->serializeResponse($request, $data);
    }

    public function searchWithNullableErrors(
        Request $request,
        SearchQueryRequest $searchRequest,
        ?ConstraintViolationListInterface $errors
    ): Response {
        $data = [
            'dto' => $searchRequest,
            'errors' => $errors,
        ];

        return $this->serializeResponse($request, $data);
    }

    public function searchException(
        Request $request,
        SearchQueryRequest $searchRequest
    ): Response {
        return $this->serializeResponse($request, $searchRequest);
    }

    public function userTokenAction(
        Request $request,
        UserTokenRequest $userTokenRequest,
        ConstraintViolationListInterface $errors
    ): Response {
        $data = [
            'dto' => $userTokenRequest,
            'errors' => $errors,
        ];

        return $this->serializeResponse($request, $data);
    }

    public function userTokenExceptionAction(
        Request $request,
        UserTokenRequest $userTokenRequest
    ): Response {
        return $this->serializeResponse($request, $userTokenRequest);
    }

    public function constraintViolationListAction(
        Request $request,
        ConstraintViolationListInterface $errors
    ): Response {
        return $this->serializeResponse($request, $errors);
    }

    public function multipleObjects(
        Request $request,
        UserTokenRequest $userTokenRequest,
        ConstraintViolationListInterface $userTokenErrors,
        SearchQueryRequest $searchQueryRequest,
        ConstraintViolationListInterface $searchQueryErrors,
        UserRegistrationRequest $userRegistrationRequest,
        ConstraintViolationListInterface $userRegistrationErrors
    ): Response {
        $data = [
            'userToken' => [
                'dto' => $userTokenRequest,
                'errors' => $userTokenErrors,
            ],
            'searchQuery' => [
                'dto' => $searchQueryRequest,
                'errors' => $searchQueryErrors,
            ],
            'userRegistration' => [
                'dto' => $userRegistrationRequest,
                'errors' => $userRegistrationErrors,
            ],
        ];

        return $this->serializeResponse($request, $data);
    }

    public function limitAction(
        Request $request,
        LimitQueryRequest $limitRequest,
        ?ConstraintViolationListInterface $errors
    ): Response {
        return $this->serializeResponse(
            $request,
            [
                'dto' => $limitRequest,
                'errors' => $errors,
            ]
        );
    }

    public function constructRequestAction(
        Request $request,
        ObjectFromRequest $dto,
        ConstraintViolationListInterface $errors
    ): Response {
        return $this->serializeResponse(
            $request,
            [
                'dto' => $dto,
                'errors' => $errors,
            ]
        );
    }

    public function constructRequestExceptionAction(
        Request $request,
        ObjectFromRequest $dto
    ): Response {
        return $this->serializeResponse(
            $request,
            $dto
        );
    }
}
