<?php

declare(strict_types=1);

namespace Nelexa\RequestDtoBundle\Tests\App\Controller;

use Nelexa\RequestDtoBundle\Tests\App\Request\SearchQueryRequest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class SearchNullableErrorsController extends AbstractController
{
    public function __invoke(
        Request $request,
        SearchQueryRequest $searchRequest,
        ?ConstraintViolationListInterface $errors
    ): Response {
        $data = [
            'dto' => $searchRequest,
            'errors' => $errors,
        ];

        return $this->serializeResponse($request, $data);
    }
}
