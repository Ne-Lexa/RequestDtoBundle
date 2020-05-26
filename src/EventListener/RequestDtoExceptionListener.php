<?php

declare(strict_types=1);

namespace Nelexa\RequestDtoBundle\EventListener;

use Nelexa\RequestDtoBundle\Exception\RequestDtoValidationException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\Serializer\SerializerInterface;

final class RequestDtoExceptionListener
{
    private SerializerInterface $serializer;

    /**
     * ExceptionListener constructor.
     */
    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if ($exception instanceof RequestDtoValidationException) {
            $contentType = 'json';
            $responseObject = $this->serializer->serialize($exception, $contentType);
            $event->setResponse(
                new JsonResponse(
                    $responseObject,
                    $exception->getStatusCode(),
                    [...$exception->getHeaders(), 'Content-Type' => 'application/problem+json'],
                    true
                )
            );
        }
    }
}
