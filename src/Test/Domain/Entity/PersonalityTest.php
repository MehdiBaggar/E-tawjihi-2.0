<?php

namespace App\Test\Domain\Entity;

use App\Test\Domain\Entity\Question\AbstractQuestion;
use App\Test\Domain\Entity\Question\QuestionPersonality;
use App\Test\Domain\ValueObject\Enum\TestType;

class PersonalityTest extends AbstractTest
{


    public function __construct(
        ?int $id,
        string $title,
        string $description,
        bool $isActive,
        int $estimatedDuration,
        int $order,
        array $questions = []
    ) {
        parent::__construct($id, $title, $description, TestType::PERSONALITY, $isActive, $estimatedDuration, $order);
        foreach ($questions as $q) {
            if (!$q instanceof QuestionPersonality) {
                throw new \InvalidArgumentException("Toutes les questions doivent être des instances de QuestionPersonality");
            }
        }
        $this->questions = $questions;
    }

    public function getQuestions(): array
    {
        return $this->questions;
    }
    public function addQuestion(AbstractQuestion $question): void
    {
        if (!$question instanceof QuestionPersonality) {
            throw new \InvalidArgumentException("Seules les questions de type QuestionPersonality sont autorisées.");
        }

        $this->questions[] = $question;
    }


}
