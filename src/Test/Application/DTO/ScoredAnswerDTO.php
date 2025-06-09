<?php

namespace App\Test\Application\DTO;

use App\Test\Domain\ValueObject\Enum\RIASECType;

class ScoredAnswerDTO
{
    public int $questionId;
    public RIASECType $riasecType;
    public int $score;

    public function __construct(int $questionId, RIASECType $riasecType, int $score)
    {
        $this->questionId = $questionId;
        $this->riasecType = $riasecType;
        $this->score = $score;
    }
}
