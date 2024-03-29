<?php

declare(strict_types=1);

namespace Nelexa\RequestDtoBundle\Tests\App\Request;

use Nelexa\RequestDtoBundle\Dto\QueryObjectInterface;
use Symfony\Component\Validator\Constraints as Assert;

class SearchQueryRequest implements QueryObjectInterface
{
    /** @Assert\NotBlank */
    public ?string $query = null;

    public string $page = '1';
}
