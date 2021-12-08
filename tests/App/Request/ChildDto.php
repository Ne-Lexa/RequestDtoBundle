<?php

declare(strict_types=1);

namespace Nelexa\RequestDtoBundle\Tests\App\Request;

use Symfony\Component\Validator\Constraints as Assert;

class ChildDto
{
    /** @Assert\NotBlank */
    public $id;
}
