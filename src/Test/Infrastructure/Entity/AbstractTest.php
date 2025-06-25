<?php

namespace App\Test\Infrastructure\Entity;

use App\Test\Infrastructure\Entity\Question\AbstractQuestion;
use App\Test\Infrastructure\Entity\Resultat\ResultatTest;
use App\Test\Infrastructure\Repository\TestPersonalityRepository;
use App\User\Infrastructure\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: TestPersonalityRepository::class)]
#[ORM\Table(name: "tests")]
#[ORM\InheritanceType("SINGLE_TABLE")]
#[ORM\DiscriminatorColumn(name: "test_type", type: "string")]
#[ORM\DiscriminatorMap([
    "personality" => TestPersonality::class,
    // Add other test types here
])]
abstract class AbstractTest
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    protected ?int $id = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    protected ?string $title = null;

    #[ORM\Column(type: "text", length: 255, nullable: true)]
    protected ?string $description = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    protected ?string $type = null;

    #[ORM\Column(type: "boolean", nullable: true)]
    protected ?bool $isActive = null;

    #[ORM\Column(type: "integer", nullable: true)]
    protected ?int $estimatedDuration = null;

    #[ORM\Column(name: "sort_order", type: "integer", nullable: true)]
    private int $sortOrder;

    #[ORM\Column(type: "datetime_immutable", nullable: true)]
    protected ?DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: "datetime_immutable", nullable: true)]
    protected ?DateTimeImmutable $updatedAt = null;

    #[ORM\OneToMany(mappedBy: "test", targetEntity: ResultatTest::class, cascade: ["persist", "remove"], orphanRemoval: true)]
    private Collection $resultats;

    #[ORM\ManyToMany(targetEntity: AbstractQuestion::class, cascade: ['persist'])]
    #[ORM\JoinTable(name: "test_questions")]
    protected Collection $questions;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: "user_id", nullable: false)]
    private ?User $user = null;

    public function __construct()
    {

        $this->createdAt = new DateTimeImmutable();
        $this->questions = new ArrayCollection();
        $this->resultats = new ArrayCollection();
    }

    // Getters and setters...
    public function getId(): ?int { return $this->id; }

    public function getTitle(): ?string { return $this->title; }
    public function setTitle(?string $title): self { $this->title = $title; return $this; }

    public function getDescription(): ?string { return $this->description; }
    public function setDescription(?string $description): self { $this->description = $description; return $this; }

    public function getType(): ?string { return $this->type; }
    public function setType(?string $type): self { $this->type = $type; return $this; }

    public function isActive(): ?bool { return $this->isActive; }
    public function setIsActive(?bool $isActive): self { $this->isActive = $isActive; return $this; }

    public function getEstimatedDuration(): ?int { return $this->estimatedDuration; }
    public function setEstimatedDuration(?int $duration): self { $this->estimatedDuration = $duration; return $this; }

    public function getOrder(): ?int { return $this->sortOrder; }
    public function setOrder(?int $order): self { $this->sortOrder = $order; return $this; }

    public function getCreatedAt(): ?DateTimeImmutable { return $this->createdAt; }
    public function setCreatedAt(?DateTimeImmutable $createdAt): self { $this->createdAt = $createdAt; return $this; }

    public function getUpdatedAt(): ?DateTimeImmutable { return $this->updatedAt; }
    public function markAsUpdated(): void { $this->updatedAt = new DateTimeImmutable(); }

    public function getResultats(): Collection
    {
        return $this->resultats;
    }

    public function addQuestion(AbstractQuestion $question): void
    {
        if (!$this->questions->contains($question)) {
            $this->questions->add($question);
        }

    }
    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;
        return $this;
    }




}
