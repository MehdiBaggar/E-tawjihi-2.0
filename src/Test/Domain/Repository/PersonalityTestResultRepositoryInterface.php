<?php

namespace App\Test\Domain\Repository;

interface PersonalityTestResultRepositoryInterface
{
    public function findResultsByTest(int $testId): ?array;

    public function saveResults(int $testId, array $results, array $rawAnswers = []): void;

}