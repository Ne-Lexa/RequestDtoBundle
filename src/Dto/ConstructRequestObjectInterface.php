<?php

declare(strict_types=1);

namespace Nelexa\RequestDtoBundle\Dto;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

interface ConstructRequestObjectInterface extends RequestDtoInterface
{
    /**
     * @throws BadRequestHttpException
     */
    public function __construct(Request $request);
}
