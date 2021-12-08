<?php

declare(strict_types=1);

namespace Nelexa\RequestDtoBundle\Tests\App\Request;

use Nelexa\RequestDtoBundle\Dto\RequestBodyObjectInterface;
use Symfony\Component\Validator\Constraints as Assert;

class RootBodyRequest implements RequestBodyObjectInterface
{
    /** @Assert\NotBlank */
    public $id;

    /**
     * @Assert\NotBlank
     * @Assert\Valid
     */
    public ?ChildDto $child = null;
}
