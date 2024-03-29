<?php

declare(strict_types=1);

namespace Nelexa\RequestDtoBundle\Tests\App\Request;

use Nelexa\RequestDtoBundle\Dto\RequestBodyObjectInterface;
use Symfony\Component\Validator\Constraints as Assert;

class UserTokenRequest implements RequestBodyObjectInterface
{
    /** @Assert\NotBlank */
    public ?string $token = null;
}
