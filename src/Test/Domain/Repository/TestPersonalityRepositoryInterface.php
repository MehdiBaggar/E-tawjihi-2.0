<?php

namespace App\Test\Domain\Repository;
use App\Test\Domain\Entity\PersonalityTest;
use App\User\Infrastructure\Entity\User;

interface TestPersonalityRepositoryInterface
{
    public function findById(int $id): ?PersonalityTest;
    public function save(PersonalityTest $test): void;
    public function findByUserId(int $userId): ?PersonalityTest;
}