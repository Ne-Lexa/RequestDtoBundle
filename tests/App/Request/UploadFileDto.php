<?php

declare(strict_types=1);

namespace Nelexa\RequestDtoBundle\Tests\App\Request;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadFileDto
{
    public ?UploadedFile $file = null;
}
