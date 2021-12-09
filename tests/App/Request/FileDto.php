<?php

declare(strict_types=1);

namespace Nelexa\RequestDtoBundle\Tests\App\Request;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;

class FileDto
{
    /** @Assert\NotBlank(allowNull=true) */
    public ?int $id = null;

    /**
     * @Assert\NotBlank(allowNull=true)
     * @Assert\File
     */
    public ?File $file = null;

    /**
     * @Assert\NotNull
     * @Assert\Type("bool")
     */
    public ?bool $delete = false;

    /** @Assert\Type("int") */
    public int $position = 0;
}
