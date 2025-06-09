<?php

namespace App\Test\Application\Service;

use App\Test\Application\DTO\ScoredAnswerDTO;
use App\Test\Domain\ValueObject\Enum\RIASECType;

class PersonalityTestScoringService
{
    /**
     * Calculates the total score per RIASEC category.
     *
     * @param ScoredAnswerDTO[] $answers
     * @return array<string, int> Associative array: [ 'REALISTIC' => 12, ... ]
     */
    public function calculateScores(array $answers): array
    {
        $scores = [];

        foreach ($answers as $answer) {
            if (!$answer instanceof ScoredAnswerDTO) {
                continue;
            }

            $typeKey = $answer->riasecType->value;

            if (!isset($scores[$typeKey])) {
                $scores[$typeKey] = 0;
            }

            $scores[$typeKey] += $answer->score;
        }

        return $scores;
    }
}
