<?php

declare(strict_types=1);

namespace Nelexa\RequestDtoBundle\Tests\App\Controller;

use Nelexa\RequestDtoBundle\Tests\App\Request\RootBodyRequest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class RootBodyController extends AbstractController
{
    public function __invoke(
        RootBodyRequest $bodyRequest,
        ConstraintViolationListInterface $errors,
        Request $request
    ): Response {
        $data = [
            'dto' => $bodyRequest,
            'errors' => $errors,
        ];

        return $this->serializeResponse($request, $data);
    }
}
