<?php

namespace App\User\Infrastructure\Repository;

use App\User\Domain\Entity\User as DomainUser;
use App\User\Domain\Repository\UserRepositoryInterface;
use App\User\Infrastructure\Entity\User as DoctrineUser;
use App\User\Infrastructure\Mapper\UserMapper;
use Doctrine\ORM\EntityManagerInterface;

class UserRepository implements UserRepositoryInterface
{
    public function __construct(private EntityManagerInterface $em) {}
    public function findAll(): array
    {
        $doctrineUsers = $this->em->getRepository(DoctrineUser::class)->findAll();

        return array_map(function (DoctrineUser $user) {
            return UserMapper::toDomain($user);
        }, $doctrineUsers);
    }
    public function findAllActive(): array
    {
        $doctrineUsers = $this->em->getRepository(DoctrineUser::class)
            ->findBy(['isArchived' => false]);

        return array_map(function (DoctrineUser $user) {
            return UserMapper::toDomain($user);
        }, $doctrineUsers);
    }
    public function findAllArchived(): array
    {
        $doctrineUsers = $this->em->getRepository(DoctrineUser::class)
            ->findBy(['isArchived' => true]);

        return array_map(function (DoctrineUser $user) {
            return UserMapper::toDomain($user);
        }, $doctrineUsers);
    }
    public function findById(int $id): ?DomainUser
    {
        $doctrineUser = $this->em->getRepository(DoctrineUser::class)->find($id);
        return $doctrineUser ? UserMapper::toDomain($doctrineUser) : null;
    }

    public function findByEmail(string $email): ?DomainUser
    {
        $doctrineUser = $this->em->getRepository(DoctrineUser::class)->findOneBy(['email' => $email]);
        return $doctrineUser ? UserMapper::toDomain($doctrineUser) : null;
    }
    public function findByPhoneNumber(string $phone): ?DomainUser
    {
        $doctrineUser = $this->em->getRepository(DoctrineUser::class)->findOneBy(['phoneNumber' => $phone]);
        return $doctrineUser ? UserMapper::toDomain($doctrineUser) : null;
    }

    public function save(DomainUser $user): void
    {
        $repository = $this->em->getRepository(DoctrineUser::class);
        $existing = $repository->find($user->getId());

        if (!$existing) {
            $doctrineUser = UserMapper::toDoctrine($user);
            $this->em->persist($doctrineUser);
        } else {
            UserMapper::updateDoctrineFromDomain($user, $existing);
        }

        $this->em->flush();
    }
    public function remove(DomainUser $user): void
    {
        $doctrineUser = $this->em->getRepository(DoctrineUser::class)->find($user->getId());

        if ($doctrineUser) {
            $this->em->remove($doctrineUser);
            $this->em->flush();
        }
    }
    public function savePassword(int $userId, string $hashedPassword): void
    {
        $doctrineUser = $this->em->getRepository(DoctrineUser::class)->find($userId);

        if (!$doctrineUser) {
            throw new \RuntimeException("User not found.");
        }

        $doctrineUser->setPassword($hashedPassword);
        $this->em->flush();
    }
}
