<?php

declare(strict_types=1);

namespace Nelexa\RequestDtoBundle\Normalizer;

use Nelexa\RequestDtoBundle\Exception\RequestDtoValidationException;
use Symfony\Component\Serializer\Normalizer\ConstraintViolationListNormalizer;
use Symfony\Component\Serializer\Normalizer\ProblemNormalizer;

class RequestDtoExceptionNormalizer extends ProblemNormalizer
{
    private ConstraintViolationListNormalizer $normalizer;

    private bool $debug;

    public function __construct(
        ConstraintViolationListNormalizer $normalizer,
        bool $debug = false,
        array $defaultContext = []
    ) {
        parent::__construct($debug, $defaultContext);
        $this->normalizer = $normalizer;
        $this->debug = $debug;
    }

    /**
     * @param RequestDtoValidationException $object
     * @param mixed|null                    $format
     *
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function normalize($object, $format = null, array $context = []): array
    {
        $context += [
            'type' => 'https://tools.ietf.org/html/rfc7807',
        ];
        $data = $this->normalizer->normalize($object->getErrors(), $format, $context);
        $data['status'] = $object->getStatusCode();

        if ($this->debug) {
            $data['class'] = \get_class($object);
            $data['trace'] = $object->getTrace();
        }

        return $data;
    }

    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof RequestDtoValidationException;
    }
}
