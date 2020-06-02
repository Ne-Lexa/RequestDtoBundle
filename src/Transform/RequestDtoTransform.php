<?php

declare(strict_types=1);

namespace Nelexa\RequestDtoBundle\Transform;

use Nelexa\RequestDtoBundle\Dto\ConstructRequestObjectInterface;
use Nelexa\RequestDtoBundle\Dto\QueryObjectInterface;
use Nelexa\RequestDtoBundle\Dto\RequestBodyObjectInterface;
use Nelexa\RequestDtoBundle\Dto\RequestObjectInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class RequestDtoTransform
{
    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @return QueryObjectInterface|RequestObjectInterface|RequestBodyObjectInterface|ConstructRequestObjectInterface
     */
    public function transform(Request $request, string $className, string $format, array $context = []): object
    {
        $context += [
            AbstractObjectNormalizer::DISABLE_TYPE_ENFORCEMENT => true,
        ];

        if (is_a($className, QueryObjectInterface::class, true)) {
            $dto = $this->serializer->denormalize(
                $request->query->all(),
                $className,
                $format,
                $context
            );
        } elseif (is_a($className, RequestObjectInterface::class, true)) {
            $dto = $this->serializer->denormalize(
                $request->isMethod('GET') || $request->isMethod('HEAD') ?
                    $request->query->all() :
                    $request->request->all(),
                $className,
                $format,
                $context
            );
        } elseif (is_a($className, RequestBodyObjectInterface::class, true)) {
            $dto = $this->serializer->deserialize(
                $request->getContent(),
                $className,
                $format,
                $context
            );
        } elseif (is_a($className, ConstructRequestObjectInterface::class, true)) {
            $dto = new $className($request);
        } else {
            throw new \RuntimeException('Class ' . $className . ' is not supported.');
        }

        return $dto;
    }
}
