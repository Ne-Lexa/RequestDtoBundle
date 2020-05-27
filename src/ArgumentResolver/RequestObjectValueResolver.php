<?php

declare(strict_types=1);

namespace Nelexa\RequestDtoBundle\ArgumentResolver;

use Nelexa\RequestDtoBundle\Dto\RequestObjectInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class RequestObjectValueResolver extends QueryObjectValueResolver
{
    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        return is_a($argument->getType(), RequestObjectInterface::class, true);
    }

    protected function getData(Request $request): array
    {
        static $queryMethods = [Request::METHOD_GET, Request::METHOD_HEAD];

        if (!\in_array($request->getMethod(), $queryMethods, true)) {
            return $request->request->all();
        }

        return parent::getData($request);
    }
}
