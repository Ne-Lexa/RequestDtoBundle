<?php

declare(strict_types=1);

namespace Nelexa\RequestDtoBundle\Tests\App\Controller;

use Nelexa\RequestDtoBundle\Tests\App\Request\UserRegistrationRequest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BadRegistrationController extends AbstractController
{
    public function __invoke(
        Request $request,
        UserRegistrationRequest $userRegistration
    ): Response {
        return $this->serializeResponse($request, $userRegistration);
    }
}
