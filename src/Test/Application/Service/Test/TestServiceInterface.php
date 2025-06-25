<?php

namespace App\Test\Application\Service\Test;

use App\Test\Domain\Entity\AbstractTest;

interface TestServiceInterface
{
    public function loadTest(int $testId): ?AbstractTest;

    public function evaluate(array $answers): array;
    public function interpretResults(mixed $result): array;
}
