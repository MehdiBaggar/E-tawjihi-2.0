<?php

namespace App\Test\Domain\Entity\Question;

use App\Test\Domain\Entity\Question\AbstractQuestion;
use App\Test\Domain\ValueObject\Enum\RIASECType;
use App\Test\Domain\ValueObject\Enum\TestType;

class QuestionPersonality extends AbstractQuestion
{
    private RIASECType $riasecType;
    private int $scoreWeight;

    public function __construct(
        int $id,
        string $text,
        RIASECType $riasecType,
        int $scoreWeight
    ) {
        parent::__construct($id, $text);
        $this->riasecType = $riasecType;
        $this->scoreWeight = $scoreWeight;
    }

    public function getRIASECType(): RIASECType
    {
        return $this->riasecType;
    }

    public function getScoreWeight(): int
    {
        return $this->scoreWeight;
    }

    public function getTestType(): TestType
    {
        return TestType::PERSONALITY;
    }
}
