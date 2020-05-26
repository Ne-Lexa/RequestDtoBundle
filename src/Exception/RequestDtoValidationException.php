<?php

declare(strict_types=1);

namespace Nelexa\RequestDtoBundle\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class RequestDtoValidationException extends HttpException
{
    private ConstraintViolationListInterface $errors;

    public function __construct(
        ConstraintViolationListInterface $errors,
        ?string $message = null,
        ?\Throwable $previous = null,
        array $headers = [],
        ?int $code = 0
    ) {
        parent::__construct(400, $message, $previous, $headers, $code);
        $this->errors = $errors;
    }

    public function getErrors(): ConstraintViolationListInterface
    {
        return $this->errors;
    }
}
