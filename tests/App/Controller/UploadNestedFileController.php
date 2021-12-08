<?php

declare(strict_types=1);

namespace Nelexa\RequestDtoBundle\Tests\App\Controller;

use Nelexa\RequestDtoBundle\Tests\App\Request\UploadNestedFileRequest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UploadNestedFileController extends AbstractController
{
    public function __invoke(
        UploadNestedFileRequest $uploadFileRequest,
        Request $request
    ): Response {
        $data = [
            'dto' => $uploadFileRequest,
        ];

        return $this->serializeResponse($request, $data);
    }
}
