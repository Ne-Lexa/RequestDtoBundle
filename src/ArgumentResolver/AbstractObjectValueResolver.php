<?php

declare(strict_types=1);

namespace Nelexa\RequestDtoBundle\ArgumentResolver;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class AbstractObjectValueResolver implements ArgumentValueResolverInterface
{
    protected SerializerInterface $serializer;

    protected ValidatorInterface $validator;

    /**
     * AbstractObjectValueResolver constructor.
     */
    public function __construct(SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $this->serializer = $serializer;
        $this->validator = $validator;
    }

    abstract protected function serialize(Request $request, ArgumentMetadata $argument, string $format): object;

    protected function getSerializeFormat(Request $request): string
    {
        static $supportFormats = ['json', 'xml'];
        static $defaultFormat = 'json';

        $format = $request->getContentType() ?? $defaultFormat;

        if (!\in_array($format, $supportFormats, true)) {
            $format = $defaultFormat;
        }

        return $format;
    }

    /**
     * @return iterable
     */
    public function resolve(Request $request, ArgumentMetadata $argument)
    {
        $format = $this->getSerializeFormat($request);

        try {
            $obj = $this->serialize($request, $argument, $format);
        } catch (\TypeError | NotEncodableValueException $e) {
            $problemMimeType = 'application/problem+' . $format;

            throw new HttpException(
                400,
                'Bad Request',
                $e,
                [
                    'Content-Type' => $problemMimeType,
                ]
            );
        }

        $request->attributes->set(
            ConstraintViolationListValueResolver::REQUEST_ATTR_KEY,
            $this->validator->validate($obj)
        );

        yield $obj;
    }
}
