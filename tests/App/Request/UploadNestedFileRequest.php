<?php

declare(strict_types=1);

namespace Nelexa\RequestDtoBundle\Tests\App\Request;

use Nelexa\RequestDtoBundle\Dto\RequestObjectInterface;
use Symfony\Component\Validator\Constraints as Assert;

class UploadNestedFileRequest implements RequestObjectInterface
{
    /** @Assert\NotBlank */
    public ?int $id = null;

    /**
     * @Assert\NotBlank
     * @Assert\Type("\Nelexa\RequestDtoBundle\Tests\App\Request\EmbedDtoFile")
     * @Assert\Valid
     */
    public ?EmbedDtoFile $dtoFile = null;
}
