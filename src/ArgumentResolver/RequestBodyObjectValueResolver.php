<?php

declare(strict_types=1);

namespace Nelexa\RequestDtoBundle\ArgumentResolver;

use Nelexa\RequestDtoBundle\Dto\RequestBodyObjectInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;

final class RequestBodyObjectValueResolver extends AbstractObjectValueResolver
{
    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        return is_a($argument->getType(), RequestBodyObjectInterface::class, true);
    }

    protected function serialize(Request $request, ArgumentMetadata $argument): object
    {
        try {
            return $this->serializer->deserialize(
                $request->getContent(),
                $argument->getType(),
                $this->getSerializeFormat($request)
            );
        } catch (NotEncodableValueException $e) {
            throw new HttpException(
                400,
                'Bad Request',
                $e,
                [
                    'Content-Type' => 'application/problem+json',
                ]
            );
        }
    }
}
