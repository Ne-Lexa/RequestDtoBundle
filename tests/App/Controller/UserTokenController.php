<?php

declare(strict_types=1);

namespace Nelexa\RequestDtoBundle\Tests\App\Controller;

use Nelexa\RequestDtoBundle\Tests\App\Request\UserTokenRequest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class UserTokenController extends AbstractController
{
    public function __invoke(
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
}
