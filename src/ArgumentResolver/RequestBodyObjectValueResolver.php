<?php

declare(strict_types=1);

namespace Nelexa\RequestDtoBundle\ArgumentResolver;

use Nelexa\RequestDtoBundle\Dto\RequestBodyObjectInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;

final class RequestBodyObjectValueResolver extends AbstractObjectValueResolver
{
    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        return is_a($argument->getType(), RequestBodyObjectInterface::class, true);
    }

    protected function serialize(Request $request, ArgumentMetadata $argument, string $format): object
    {
        return $this->serializer->deserialize(
            $request->getContent(),
            $argument->getType(),
            $format,
            [
                AbstractObjectNormalizer::DISABLE_TYPE_ENFORCEMENT => true,
            ]
        );
    }
}
