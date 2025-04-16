<?php

namespace App\Domain\VO;

use Webmozart\Assert\Assert;

class TodoId
{
    private function __construct(private readonly string $value)
    {
    }

    public static function create(string $id): self
    {
        Assert::uuid($id, 'Invalid UUID format');
        return new self($id);
    }

    public function __toString(): string
    {
        return $this->value;
    }
}