<?php

declare(strict_types=1);

namespace Nelexa\RequestDtoBundle\Tests\App\Controller;

use Nelexa\RequestDtoBundle\Tests\App\Request\SearchQueryRequest;
use Nelexa\RequestDtoBundle\Tests\App\Request\UserRegistrationRequest;
use Nelexa\RequestDtoBundle\Tests\App\Request\UserTokenRequest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class MultipleObjectsController extends AbstractController
{
    public function __invoke(
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
}
