<?php
// src/User/Domain/ValueObject/PersonalInfo.php
namespace App\User\Domain\ValueObject;

use DateTimeInterface;

class PersonalInfo
{
    private ?string $firstName;
    private ?string $lastName;
    private ?DateTimeInterface $dateOfBirth;


    public function __construct(
        ?string $firstName,
        ?string $lastName,
        ?DateTimeInterface $dateOfBirth,
        ?string $sex,



    ) {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->dateOfBirth = $dateOfBirth;
        $this->sex = $sex;

    }

    public function getFirstName(): ?string { return $this->firstName; }
    public function getLastName(): ?string { return $this->lastName; }
    public function getDateOfBirth(): ?DateTimeInterface { return $this->dateOfBirth; }
    public function getSex(): ?string { return $this->sex; }


}
