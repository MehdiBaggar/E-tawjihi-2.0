<?php

namespace App\User\Application\Service;

use App\User\Application\DTO\RegisterUserDTO;
use App\User\Domain\Entity\User as DomainUser;
use App\User\Domain\Repository\UserRepositoryInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\User\Infrastructure\Entity\User as InfrastructureUser;

class RegisterUserService
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private UserPasswordHasherInterface $passwordHasher
    ) {}

    public function register(RegisterUserDTO $dto): void
    {
        // Utilisation d'un User Symfony pour générer le hash
        $dummy = new InfrastructureUser($dto->email);
        $hashedPassword = $this->passwordHasher->hashPassword($dummy, $dto->plainPassword);

        // Utilisation de la méthode statique `create()` propre
        $domainUser = DomainUser::create(
            email: $dto->email,
            password: $hashedPassword,
        );
        $domainUser->setPhoneNumber($dto->phoneNumber);

        $this->userRepository->save($domainUser);
    }
}
