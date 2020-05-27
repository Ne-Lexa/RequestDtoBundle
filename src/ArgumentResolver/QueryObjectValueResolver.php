<?php

declare(strict_types=1);

namespace Nelexa\RequestDtoBundle\ArgumentResolver;

use Nelexa\RequestDtoBundle\Dto\QueryObjectInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;

class QueryObjectValueResolver extends AbstractObjectValueResolver
{
    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        return is_a($argument->getType(), QueryObjectInterface::class, true);
    }

    protected function serialize(Request $request, ArgumentMetadata $argument, string $format): object
    {
        return $this->serializer->denormalize(
            $this->getData($request),
            $argument->getType(),
            $format,
            [
                AbstractObjectNormalizer::DISABLE_TYPE_ENFORCEMENT => true,
            ]
        );
    }

    protected function getData(Request $request): array
    {
        return $request->query->all();
    }
}
