<?php

declare(strict_types=1);

namespace Nelexa\RequestDtoBundle\Tests\App\Request;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;

class EmbedDtoFile
{
    /**
     * @Assert\NotBlank
     * @Assert\File(
     *     maxSize="20Mi",
     *     mimeTypes={
     *         "text/plain"
     *     },
     *     mimeTypesMessage="Please upload txt file (uploaded {{ type }} type)"
     * )
     */
    public ?File $file = null;

    /** @Assert\NotBlank */
    public ?int $filesize = null;

    /** @Assert\NotBlank */
    public ?string $mimeType = null;
}
