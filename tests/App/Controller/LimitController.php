<?php

declare(strict_types=1);

namespace Nelexa\RequestDtoBundle\Tests\App\Controller;

use Nelexa\RequestDtoBundle\Tests\App\Request\LimitQueryRequest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class LimitController extends AbstractController
{
    public function __invoke(
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
}
