<?php

declare(strict_types=1);

namespace Nelexa\RequestDtoBundle\Tests\App\Request;

use Nelexa\RequestDtoBundle\Dto\RequestObjectInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

class UploadSingleFileRequest implements RequestObjectInterface
{
    /** @Assert\NotBlank */
    public ?int $id = null;

    /**
     * @Assert\NotBlank,
     * @Assert\File(
     *     maxSize="200Mi",
     *     mimeTypes={
     *         "application/x-ms-dos-executable",
     *         "application/x-msdos-program",
     *         "application/x-msdownload",
     *         "application/x-dosexec",
     *         "application/x-msi"
     *     },
     *     mimeTypesMessage="Please upload a valid EXE or MSI file"
     * )
     */
    public ?UploadedFile $file = null;
}
