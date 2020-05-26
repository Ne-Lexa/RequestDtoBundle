<?php

declare(strict_types=1);

namespace Nelexa\RequestDtoBundle\ArgumentResolver;

use Nelexa\RequestDtoBundle\Dto\QueryObjectInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class QueryObjectValueResolver extends AbstractObjectValueResolver
{
    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        return is_a($argument->getType(), QueryObjectInterface::class, true);
    }

    protected function serialize(Request $request, ArgumentMetadata $argument): object
    {
        return $this->serializer->denormalize(
            $request->query->all(),
            $argument->getType(),
            $this->getSerializeFormat($request)
        );
    }
}
