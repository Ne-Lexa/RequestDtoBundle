<?php

declare(strict_types=1);

namespace Nelexa\RequestDtoBundle\Tests\App\Controller;

use Nelexa\RequestDtoBundle\Tests\App\Request\RootFormRequest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class RootFormController extends AbstractController
{
    public function __invoke(
        RootFormRequest $formRequest,
        ConstraintViolationListInterface $errors,
        Request $request
    ): Response {
        $data = [
            'dto' => $formRequest,
            'errors' => $errors,
        ];

        return $this->serializeResponse($request, $data);
    }
}
