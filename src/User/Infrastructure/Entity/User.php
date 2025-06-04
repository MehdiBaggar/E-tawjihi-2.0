<?php

namespace App\User\Infrastructure\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use App\User\Infrastructure\Entity\PersonalInfo;
use App\User\Domain\Entity\User as DomainUser;

#[ORM\Entity()]
#[ORM\Table(name: 'users')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', unique: true)]
    private string $email;

    #[ORM\Column(type: 'string')]
    private string $password;

    #[ORM\OneToOne(mappedBy: 'user', targetEntity: PersonalInfo::class, cascade: ['persist', 'remove'])]
    private ?PersonalInfo $personalInfo = null;

    #[ORM\OneToOne(targetEntity: AcademicInfo::class, mappedBy: 'user', cascade: ['persist', 'remove'])]
    private ?AcademicInfo $academicInfo = null;


    #[ORM\Column(type: 'string', unique: true)]
    private string $phoneNumber;



    #[ORM\Column(type: 'json')]
    private array $roles = [];

    #[ORM\Column(type: 'boolean')]
    private bool $isArchived = false;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;


    public function __construct(string $email)
    {
        $this->email = $email;
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): int { return $this->id; }
    public function getEmail(): string { return $this->email; }
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPassword(): string { return $this->password; }

    public function getRoles(): array
    {
        // Assure au moins ROLE_USER
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;
        return $this;
    }
    public function getUserIdentifier(): string { return $this->phoneNumber; }
    public function eraseCredentials(): void {}

    public function getCreatedAt():\DateTimeImmutable
    {
        return $this->createdAt;
    }
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }
    public function isArchived(): bool
    {
        return $this->isArchived;
    }
    public function archive(): void
    {
        $this->isArchived = true;
    }
    public function unarchive(): void
    {
        $this->isArchived = false;
    }

    public function getPhoneNumber(): string { return $this->phoneNumber; }
    public function setPhoneNumber(string $phoneNumber): void { $this->phoneNumber = $phoneNumber; }


    public function getPersonalInfo(): ?PersonalInfo
    {
        return $this->personalInfo;
    }

    public function setPersonalInfo(?PersonalInfo $personalInfo): void
    {
        $this->personalInfo = $personalInfo;
    }
    public function getAcademicInfo(): ?AcademicInfo
    {
        return $this->academicInfo;
    }

    public function setAcademicInfo(?AcademicInfo $academicInfo): void
    {
        $this->academicInfo = $academicInfo;
        if ($academicInfo !== null) {
            $academicInfo->setUser($this);
        }
    }

}
