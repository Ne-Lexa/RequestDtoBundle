<?php

declare(strict_types=1);

namespace Nelexa\RequestDtoBundle\ArgumentResolver;

use Nelexa\RequestDtoBundle\Dto\ConstructRequestObjectInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class ConstructRequestObjectValueResolver extends AbstractObjectValueResolver
{
    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        return is_a($argument->getType(), ConstructRequestObjectInterface::class, true);
    }

    protected function serialize(Request $request, ArgumentMetadata $argument, string $format): object
    {
        $type = $argument->getType();

        return new $type($request);
    }
}
