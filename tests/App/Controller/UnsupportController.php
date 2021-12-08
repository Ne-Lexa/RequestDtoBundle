<?php

declare(strict_types=1);

namespace Nelexa\RequestDtoBundle\Tests\App\Controller;

use Nelexa\RequestDtoBundle\Tests\App\Request\UnsupportObjectRequest;
use Symfony\Component\HttpFoundation\Response;

class UnsupportController extends AbstractController
{
    public function __invoke(UnsupportObjectRequest $dto): Response
    {
        return new Response();
    }
}
