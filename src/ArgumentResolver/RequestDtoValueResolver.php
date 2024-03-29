<?php

declare(strict_types=1);

namespace Nelexa\RequestDtoBundle\ArgumentResolver;

use Nelexa\RequestDtoBundle\Dto\RequestDtoInterface;
use Nelexa\RequestDtoBundle\Transform\RequestDtoTransform;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\PropertyAccess\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\Exception\NotNormalizableValueException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class RequestDtoValueResolver implements ArgumentValueResolverInterface
{
    private RequestDtoTransform $transformer;

    private ValidatorInterface $validator;

    public function __construct(RequestDtoTransform $transformer, ValidatorInterface $validator)
    {
        $this->transformer = $transformer;
        $this->validator = $validator;
    }

    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        return is_a($argument->getType(), RequestDtoInterface::class, true);
    }

    private function getSerializeFormat(Request $request): string
    {
        static $supportFormats = ['json', 'xml'];
        static $defaultFormat = 'json';

        $format = $request->getContentType() ?? $defaultFormat;

        if (!\in_array($format, $supportFormats, true)) {
            $format = $defaultFormat;
        }

        return $format;
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $format = $this->getSerializeFormat($request);

        try {
            $obj = $this->transformer->transform($request, $argument->getType(), $format);
            $request->attributes->set(
                ConstraintViolationListValueResolver::REQUEST_ATTR_KEY,
                $this->validator->validate($obj)
            );

            yield $obj;

            return;
        } catch (\TypeError|NotEncodableValueException $e) {
            $exception = $e;
        } catch (NotNormalizableValueException $e) {
            $previousException = $e->getPrevious();

            if ($previousException instanceof InvalidArgumentException) {
                $e = $previousException;
            }
            $exception = $e;
        }

        $problemMimeType = 'application/problem+' . $format;

        throw new HttpException(
            400,
            'Bad Request',
            $exception,
            [
                'Content-Type' => $problemMimeType,
            ]
        );
    }
}
