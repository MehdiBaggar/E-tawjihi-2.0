<?php

namespace App\Test\Domain\Entity;
use App\Test\Domain\ValueObject\Enum\TestType;

use DateTimeImmutable;

abstract class AbstractTest
{
    protected int $id;
    protected string $title;
    protected string $description;
    protected TestType $type;
    protected bool $isActive;
    protected int $estimatedDuration;
    protected int $order;
    protected DateTimeImmutable $createdAt;
    protected ?DateTimeImmutable $updatedAt = null;

    public function __construct(
        int $id,
        string $title,
        string $description,
        TestType $type,
        bool $isActive,
        int $estimatedDuration,
        int $order
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->type = $type;
        $this->isActive = $isActive;
        $this->estimatedDuration = $estimatedDuration;
        $this->order = $order;
        $this->createdAt = new DateTimeImmutable();
    }

    // === Getters communs ===
    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getType(): TestType
    {
        return $this->type;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function getEstimatedDuration(): int
    {
        return $this->estimatedDuration;
    }

    public function getOrder(): int
    {
        return $this->order;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function markAsUpdated(): void
    {
        $this->updatedAt = new DateTimeImmutable();
    }


    abstract public function calculateScore(array $answers): float;

    abstract public function getInstructions(): string;
}
