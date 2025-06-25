<?php

namespace App\Test\Domain\Factory;

use App\Test\Domain\Entity\PersonalityTest;
use App\Test\Domain\Entity\Question\QuestionPersonality;

final class PersonalityTestFactory
{
    /**
     * @param QuestionPersonality[] $questions
     */
    public static function create(
        ?int $id,
        string $title,
        string $description,
        bool $isActive,
        int $estimatedDuration,
        int $order,
        array $questions,
        ?\DateTimeImmutable $createdAt = null,
        ?\DateTimeImmutable $updatedAt = null
    ): PersonalityTest {
        if (trim($title) === '') {
            throw new \InvalidArgumentException("Le titre ne peut pas être vide.");
        }

        if ($estimatedDuration <= 0) {
            throw new \InvalidArgumentException("La durée estimée doit être supérieure à 0.");
        }

        if ($order < 0) {
            throw new \InvalidArgumentException("L'ordre doit être positif.");
        }

        if (empty($questions)) {
            throw new \InvalidArgumentException("Le test doit contenir au moins une question.");
        }

        foreach ($questions as $q) {
            if (!$q instanceof QuestionPersonality) {
                throw new \InvalidArgumentException("Les questions doivent être de type QuestionPersonality.");
            }
        }

        return new PersonalityTest(
            $id,
            $title,
            $description,
            $isActive,
            $estimatedDuration,
            $order,
            $questions,
            $createdAt ?? new \DateTimeImmutable(),
            $updatedAt ?? new \DateTimeImmutable()
        );
    }
}
