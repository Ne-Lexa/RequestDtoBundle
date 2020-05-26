<?php

declare(strict_types=1);

namespace Nelexa\RequestDtoBundle\ArgumentResolver;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
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

    abstract protected function serialize(Request $request, ArgumentMetadata $argument): object;

    protected function getSerializeFormat(Request $request): string
    {
        static $supportFormats = ['json', 'xml', 'yaml', 'csv'];
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
        $obj = $this->serialize($request, $argument);
        $violationList = $this->validator->validate($obj);

        $request->attributes->set(
            ConstraintViolationListValueResolver::REQUEST_ATTR_KEY,
            $violationList
        );

        yield $obj;
    }
}
