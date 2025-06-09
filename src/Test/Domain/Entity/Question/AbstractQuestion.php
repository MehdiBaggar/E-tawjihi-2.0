<?php

namespace App\Test\Domain\Entity\Question;

use App\Test\Domain\ValueObject\Enum\TestType;

abstract class AbstractQuestion
{
    protected int $id;
    protected string $text;

    public function __construct(int $id, string $text)
    {
        $this->id = $id;
        $this->text = $text;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getText(): string
    {
        return $this->text;
    }

    abstract public function getTestType(): TestType;
}
