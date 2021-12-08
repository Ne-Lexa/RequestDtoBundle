<?php

declare(strict_types=1);

namespace Nelexa\RequestDtoBundle\Tests\App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ErrorsController extends AbstractController
{
    public function __invoke(
        Request $request,
        ConstraintViolationListInterface $errors
    ): Response {
        return $this->serializeResponse($request, $errors);
    }
}
