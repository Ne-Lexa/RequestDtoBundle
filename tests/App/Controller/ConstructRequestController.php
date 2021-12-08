<?php

declare(strict_types=1);

namespace Nelexa\RequestDtoBundle\Tests\App\Controller;

use Nelexa\RequestDtoBundle\Tests\App\Request\ObjectFromRequest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ConstructRequestController extends AbstractController
{
    public function __invoke(
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
}
