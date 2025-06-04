<?php

namespace App\User\Domain\Entity;

use App\User\Domain\ValueObject\AcademicInfo;
use App\User\Domain\ValueObject\PersonalInfo;
use DateTimeImmutable;

class User
{
    private int $id;
    private string $email;
    private string $password;
    private DateTimeImmutable $createdAt;
    private array $roles = [];
    private bool $isArchived = false;
    private ?string $phoneNumber = null;

    private ?PersonalInfo $personalInfo = null;
    private ?AcademicInfo $academicInfo = null;




    private function __construct() {
        $this->id = 0;
    }

    public static function create(
        string $email,
        string $password,
        array $roles = ['ROLE_USER']
    ): self {
        $user = new self();
        $user->email = $email;
        $user->password = $password;
        $user->createdAt = new DateTimeImmutable();
        $user->roles = $roles;

        return $user;
    }


    public function getId(): ?int
    {
        return $this->id;
    }
    public function getEmail(): string { return $this->email; }
    public function getPassword(): string { return $this->password; }
    public function getCreatedAt(): DateTimeImmutable { return $this->createdAt; }
    public function getRoles(): array { return $this->roles; }
    public function isArchived(): bool { return $this->isArchived; }


    // ✅ Méthodes métier (setters avec logique métier si besoin)
    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    public function archive(): void
    {
        $this->isArchived = true;
    }
    public function unarchive(): void
    {
        $this->isArchived = false;
    }

    public function setEmail(string $email): void
    {
        // 💡 Ajoute ici une règle métier (ex: validateur email)
        $this->email = $email;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function setId(int $id): User
    {
        $this->id = $id;
        return $this;
    }
    public function getPhoneNumber(): ?string { return $this->phoneNumber; }
    public function setPhoneNumber(?string $phoneNumber): void { $this->phoneNumber = $phoneNumber; }

    public function setPersonalInfo(PersonalInfo $info): void {
        $this->personalInfo = $info;
    }

    public function getPersonalInfo(): ?PersonalInfo {
        return $this->personalInfo;
    }
    public function setAcademicInfo(AcademicInfo $info): void {
        $this->academicInfo = $info;
    }

    public function getAcademicInfo(): ?AcademicInfo {
        return $this->academicInfo;
    }






}
