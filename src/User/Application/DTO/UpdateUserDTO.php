<?php
namespace App\User\Application\DTO;

class UpdateUserDTO
{
    public function __construct(
        public readonly string $id,
        public ?string $email = null,
        public ?string $password = null,
        public ?string $firstName = null,
        public ?string $lastName = null,
        public ?string $phoneNumber = null,
        public ?\DateTimeInterface $dateOfBirth = null,
        public ?string $sex = null
    ) {}
}

