<?php

namespace App\Test\Domain\Entity\Question;

use App\Test\Domain\Entity\Question\AbstractQuestion;
use App\Test\Domain\ValueObject\Enum\RIASECType;
use App\Test\Domain\ValueObject\Enum\TestType;

class QuestionPersonality extends AbstractQuestion
{
    /**
     * @var array<string, int>  // ['R' => 3, 'I' => 0, ...]
     */
    private array $traitScores = [];

    public function __construct(int $id, string $text, array $traitScores) {
        parent::__construct($id, $text);
        $this->traitScores = $traitScores;

        // Initialize traitScores with 0 for all types if not provided
        foreach (RIASECType::cases() as $type) {
            $this->traitScores[$type->value] = $traitScores[$type->value] ?? 0;
        }
    }

    public function getTraitScores(): array
    {
        return $this->traitScores;
    }

    public function setTraitScores(array $traitScores): self
    {
        $this->traitScores = $traitScores;
        return $this;
    }

    public function getTraitScore(string $trait): int
    {
        return $this->traitScores[$trait] ?? 0;
    }

    public function setTraitScore(string $trait, int $score): self
    {
        if (!array_key_exists($trait, $this->traitScores)) {
            throw new \InvalidArgumentException("Invalid RIASEC trait: $trait");
        }

        $this->traitScores[$trait] = $score;
        return $this;
    }

    public function getTestType(): TestType
    {
        return TestType::PERSONALITY;
    }

    public function setText(string $Text)
    {
        $this->text=$Text;
    }
}
