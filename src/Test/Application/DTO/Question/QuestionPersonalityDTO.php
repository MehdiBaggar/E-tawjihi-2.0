<?php

namespace App\Test\Application\DTO\Question;

use App\Test\Domain\Entity\Question\QuestionPersonality;

class QuestionPersonalityDTO
{
    public int $id;
    public string $text;
    public string $riasecType;
    public int $scoreWeight;

    public function __construct(int $id, string $text, string $riasecType, int $scoreWeight)
    {
        $this->id = $id;
        $this->text = $text;
        $this->riasecType = $riasecType;
        $this->scoreWeight = $scoreWeight;
    }

    public static function fromEntity(QuestionPersonality $question): self
    {
        return new self(
            $question->getId(),
            $question->getText(),
            $question->getRIASECType()->value,
            $question->getScoreWeight()
        );
    }
}
