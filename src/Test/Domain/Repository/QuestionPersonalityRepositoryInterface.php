<?php

namespace App\Test\Domain\Repository;

use App\Test\Domain\Entity\Question\QuestionPersonality;

interface QuestionPersonalityRepositoryInterface extends QuestionRepositoryInterface
{
    /**
     * @param string $riasecType The value of RIASECType enum (e.g., 'REALISTIC', 'INVESTIGATIVE', etc.)
     * @return QuestionPersonality[]
     */
    public function findAllByCategory(string $riasecType): array;
}
