<?php

declare(strict_types=1);

namespace Nelexa\RequestDtoBundle\EventListener;

use Nelexa\RequestDtoBundle\ArgumentResolver\ConstraintViolationListValueResolver;
use Nelexa\RequestDtoBundle\Exception\RequestDtoValidationException;
use Symfony\Component\HttpKernel\Event\ControllerArgumentsEvent;
use Symfony\Component\Validator\ConstraintViolationList;

class RequestDtoControllerArgumentListener
{
    public function onControllerArguments(ControllerArgumentsEvent $event): void
    {
        $request = $event->getRequest();

        $key = ConstraintViolationListValueResolver::REQUEST_ATTR_KEY;

        if ($request->attributes->has($key)) {
            /** @var ConstraintViolationList $violationList */
            $violationList = $request->attributes->get($key);

            if ($violationList->count() > 0) {
                throw new RequestDtoValidationException($violationList, 'Bad Request');
            }
        }
    }
}
