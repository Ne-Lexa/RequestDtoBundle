<?php

declare(strict_types=1);

namespace Nelexa\RequestDtoBundle\Examples\Dto;

use Nelexa\RequestDtoBundle\Dto\RequestObjectInterface;

class LimitQueryRequest implements RequestObjectInterface
{
    public int $offset = 0;

    public int $limit = 50;

    public bool $collapse = false;

    public ?float $freq;

    public array $array;
}
