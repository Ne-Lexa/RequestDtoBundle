<?php

declare(strict_types=1);

namespace Nelexa\RequestDtoBundle\Tests\App\Request;

use Nelexa\RequestDtoBundle\Dto\RequestObjectInterface;
use Symfony\Component\Validator\Constraints as Assert;

class FilesUpdateRequest implements RequestObjectInterface
{
    /**
     * @Assert\NotBlank
     * @Assert\Type("int")
     */
    public ?int $id = null;

    /**
     * @var \Nelexa\RequestDtoBundle\Tests\App\Request\FileDto[]
     * @Assert\All({
     *     @Assert\Type("Nelexa\RequestDtoBundle\Tests\App\Request\FileDto")
     * })
     * @Assert\Valid
     */
    public array $files = [];
}
