<?php

declare(strict_types=1);

namespace Nelexa\RequestDtoBundle\Tests\App\Controller;

use Nelexa\RequestDtoBundle\Tests\App\Request\UserRegistrationRequest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class RegistrationController extends AbstractController
{
    public function __invoke(
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
}
