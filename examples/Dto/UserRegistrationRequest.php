<?php

declare(strict_types=1);

namespace Nelexa\RequestDtoBundle\Examples\Dto;

use Nelexa\RequestDtoBundle\Dto\RequestObjectInterface;
use Symfony\Component\Validator\Constraints as Assert;

class UserRegistrationRequest implements RequestObjectInterface
{
    /** @Assert\NotBlank() */
    public ?string $name = null;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(min="6")
     */
    public ?string $password = null;

    /**
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    public ?string $email = null;
}
