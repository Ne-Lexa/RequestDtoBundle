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
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     *
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
                $request->isMethod('GET') || $request->isMethod('HEAD')
                    ? $request->query->all()
                    : self::recursiveMergeDistinctArray(
                        $request->request->all(),
                        $request->files->all()
                    ),
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

    private static function recursiveMergeDistinctArray(array $array1, array $array2): array
    {
        $merged = $array1;

        foreach ($array2 as $key => $value) {
            if (\is_array($value) && isset($merged[$key]) && \is_array($merged[$key])) {
                $merged[$key] = self::recursiveMergeDistinctArray($merged[$key], $value);
            } else {
                $merged[$key] = $value;
            }
        }

        return $merged;
    }
}
