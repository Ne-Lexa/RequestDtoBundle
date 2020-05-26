<?php

declare(strict_types=1);

namespace Nelexa\RequestDtoBundle\ArgumentResolver;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Validator\ConstraintViolationListInterface;

final class ConstraintViolationListValueResolver implements ArgumentValueResolverInterface
{
    public const REQUEST_ATTR_KEY = '_dto_violations';

    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        return is_a($argument->getType(), ConstraintViolationListInterface::class, true);
    }

    /**
     * @return iterable
     */
    public function resolve(Request $request, ArgumentMetadata $argument)
    {
        /** @var ConstraintViolationListInterface|null $errorsQueue */
        $violationList = $request->attributes->get(self::REQUEST_ATTR_KEY);

        if ($violationList === null) {
            throw new \InvalidArgumentException(
                sprintf(
                    'The action argument "%s \$%s" is required.',
                    (string) $argument->getType(),
                    $argument->getName()
                )
            );
        }

        $request->attributes->remove(self::REQUEST_ATTR_KEY);

        if ($argument->isNullable() && $violationList->count() === 0) {
            $violationList = null;
        }

        yield $violationList;
    }
}
