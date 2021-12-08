<?php

declare(strict_types=1);

namespace Nelexa\RequestDtoBundle\Tests\App\Controller;

use Nelexa\RequestDtoBundle\Tests\App\Request\UserTokenRequest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserTokenExceptionController extends AbstractController
{
    public function __invoke(
        Request $request,
        UserTokenRequest $userTokenRequest
    ): Response {
        return $this->serializeResponse($request, $userTokenRequest);
    }
}
