<?php

namespace App\User\Application\Service;

use App\User\Domain\Repository\UserRepositoryInterface;
use App\User\Application\DTO\UpdateUserDTO;
use App\User\Domain\ValueObject\PersonalInfo;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UpdateUserService
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private UserPasswordHasherInterface $passwordHasher
    ) {}

    public function update(UpdateUserDTO $dto): void
    {
        $user = $this->userRepository->findById($dto->id);

        if (!$user) {
            throw new \Exception('User not found');
        }

        if ($dto->email) {
            $user->setEmail($dto->email);
        }
        if ($dto->phoneNumber) {
            $user->setPhoneNumber($dto->phoneNumber);
        }


        if ($dto->password) {
            $dummy = new \App\User\Infrastructure\Entity\User($dto->email);
            $hashedPassword = $this->passwordHasher->hashPassword($dummy, $dto->password);
            $user->setPassword($hashedPassword);
        }

        // 👇 Mise à jour de PersonalInfo si au moins une info est présente
        if (
            $dto->firstName !== null ||
            $dto->lastName !== null ||
            $dto->dateOfBirth !== null ||
            $dto->sex !== null
        ) {
            $personalInfo = new PersonalInfo(
                $dto->firstName,
                $dto->lastName,
                $dto->dateOfBirth,
                $dto->sex
            );
            $user->setPersonalInfo($personalInfo);
        }

        $this->userRepository->save($user);
    }
}
