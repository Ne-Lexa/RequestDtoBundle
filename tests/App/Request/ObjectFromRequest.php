<?php

declare(strict_types=1);

namespace Nelexa\RequestDtoBundle\Tests\App\Request;

use Nelexa\RequestDtoBundle\Dto\ConstructRequestObjectInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\Constraints as Assert;

class ObjectFromRequest implements ConstructRequestObjectInterface
{
    /** @Assert\NotBlank */
    private string $string;

    /**
     * @Assert\NotBlank
     * @Assert\Range(min=100, max=500)
     */
    private int $integer;

    /** @Assert\IsTrue */
    private bool $boolean;

    /** @Assert\NotBlank */
    private float $float;

    /** @Assert\NotBlank() */
    private array $array;

    /**
     * @throws BadRequestHttpException
     */
    public function __construct(Request $request)
    {
        $this->string = (string) $request->request->get('s', '');
        $this->integer = $request->request->getInt('i');
        $this->boolean = $request->request->getBoolean('b', false);
        $this->float = (float) $request->request->get('f', 0);
        $this->array = $request->request->all()['a'] ?? [];
    }

    public function getString(): string
    {
        return $this->string;
    }

    public function getInteger(): int
    {
        return $this->integer;
    }

    public function isBoolean(): bool
    {
        return $this->boolean;
    }

    public function getFloat(): float
    {
        return $this->float;
    }

    public function getArray(): array
    {
        return $this->array;
    }
}
