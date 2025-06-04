<?php

namespace App\User\Application\Service;

use App\User\Application\DTO\ChangePasswordDTO;
use App\User\Domain\Repository\UserRepositoryInterface;
use App\User\Infrastructure\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\User\Domain\Entity\User as DomainUser;
use App\User\Infrastructure\Mapper\UserMapper;
use Doctrine\ORM\EntityManagerInterface;

class ChangePasswordService
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private EntityManagerInterface $em,
        private UserPasswordHasherInterface $passwordHasher
    ) {}
    public function changePassword(DomainUser $domainUser, ChangePasswordDTO $dto): void
    {
        $doctrineUser = $this->em->getRepository(User::class)->find($domainUser->getId());

        if (!$this->passwordHasher->isPasswordValid($doctrineUser, $dto->oldPassword)) {
            throw new \InvalidArgumentException("L'ancien mot de passe est incorrect.");
        }

        $hashedPassword = $this->passwordHasher->hashPassword($doctrineUser, $dto->newPassword);
        $this->userRepository->savePassword($domainUser->getId(), $hashedPassword);
    }
}
