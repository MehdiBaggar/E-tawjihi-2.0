<?php

namespace App\Test\Domain\Entity;

use App\Test\Domain\Entity\Question\AbstractQuestion;
use App\Test\Domain\ValueObject\Enum\TestType;
use App\User\Domain\Entity\User; // ajout de l'import User
use DateTimeImmutable;

abstract class AbstractTest
{
    protected ?int $id;
    protected string $title;
    protected string $description;
    protected TestType $type;
    protected bool $isActive;
    protected int $estimatedDuration;
    protected int $order;
    protected DateTimeImmutable $createdAt;
    protected ?DateTimeImmutable $updatedAt = null;

    /**
     * @var AbstractQuestion[]
     */
    protected array $questions = [];

    protected ?User $user = null;  // nouvel attribut user

    public function __construct(
        ?int $id,
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
    public function getId(): ?int
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

    // === Questions ===
    abstract public function addQuestion(AbstractQuestion $question): void;

    public function getQuestions(): array
    {
        return $this->questions;
    }

    // === User Getter & Setter ===
    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;
        return $this;
    }
    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }
}
