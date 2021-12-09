<?php

namespace Nelexa\RequestDtoBundle\Tests\App\Controller;

use Nelexa\RequestDtoBundle\Tests\App\Request\FilesUpdateRequest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class FilesUpdateController extends AbstractController
{
    public function __invoke(
        Request $request,
        FilesUpdateRequest $dto
    ): Response {
        return $this->serializeResponse(
            $request,
            [
                'dto' => $dto,
            ]
        );
    }
}
