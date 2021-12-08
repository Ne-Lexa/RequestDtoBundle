<?php

declare(strict_types=1);

namespace Nelexa\RequestDtoBundle\Tests\App\Controller;

use Nelexa\RequestDtoBundle\Tests\App\Request\SearchQueryRequest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SearchExceptionController extends AbstractController
{
    public function __invoke(
        Request $request,
        SearchQueryRequest $searchRequest
    ): Response {
        return $this->serializeResponse($request, $searchRequest);
    }
}
