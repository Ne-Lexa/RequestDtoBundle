<?php

declare(strict_types=1);

namespace Nelexa\RequestDtoBundle\Tests\App\Request;

use Nelexa\RequestDtoBundle\Dto\RequestObjectInterface;
use Symfony\Component\Validator\Constraints as Assert;

class RootFormRequest implements RequestObjectInterface
{
    /** @Assert\NotBlank */
    public $id;

    /**
     * @Assert\NotBlank
     * @Assert\Valid
     */
    public ?ChildDto $child = null;
}
