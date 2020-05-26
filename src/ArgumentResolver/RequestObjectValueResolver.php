<?php

declare(strict_types=1);

namespace Nelexa\RequestDtoBundle\ArgumentResolver;

use Nelexa\RequestDtoBundle\Dto\RequestObjectInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class RequestObjectValueResolver extends AbstractObjectValueResolver
{
    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        return is_a($argument->getType(), RequestObjectInterface::class, true);
    }

    protected function serialize(Request $request, ArgumentMetadata $argument): object
    {
        static $queryMethods = [Request::METHOD_GET, Request::METHOD_HEAD];

        $data = \in_array($request->getMethod(), $queryMethods, true) ?
            $request->query->all() :
            $request->request->all();

        return $this->serializer->denormalize($data, $argument->getType(), $this->getSerializeFormat($request));
    }
}
