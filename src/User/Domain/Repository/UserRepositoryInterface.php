<?php

namespace App\User\Domain\Repository;

use App\User\Domain\Entity\User;

interface UserRepositoryInterface
{
    public function findById(int $id): ?User;
    public function findByEmail(string $email): ?User;
    public function save(User $user): void;
    public function findAll(): array;
    public function findAllActive(): array;
    public function findAllArchived(): array;
    public function savePassword(int $userId, string $hashedPassword): void;
}
