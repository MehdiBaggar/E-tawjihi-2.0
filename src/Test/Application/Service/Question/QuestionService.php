<?php

namespace App\Test\Application\Service\Question;

use App\Test\Domain\Repository\QuestionPersonalityRepositoryInterface;
use App\Test\Domain\Entity\Question\QuestionPersonality;

class QuestionService
{
    public function __construct(
        private readonly QuestionPersonalityRepositoryInterface $questionPersonalityRepository
    ) {}

    public function loadRandomQuestions(int $count): array
    {
        $allQuestions = $this->questionPersonalityRepository->findAll();

        if (count($allQuestions) <= $count) {
            return $allQuestions;
        }

        $randomKeys = array_rand($allQuestions, $count);

        if (!is_array($randomKeys)) {
            $randomKeys = [$randomKeys];
        }

        return array_map(fn($key) => $allQuestions[$key], $randomKeys);
    }

    public function loadBalancedQuestions(): array
    {
        $allQuestions = $this->questionPersonalityRepository->findAll();

        $groupedByDominant = [
            'R' => [],
            'I' => [],
            'A' => [],
            'S' => [],
            'E' => [],
            'C' => [],
        ];

        foreach ($allQuestions as $question) {
            $traitScores = $question->getTraitScores();

            if (!is_array($traitScores) || empty($traitScores)) {
                continue;
            }

            // Trouver le ou les traits avec le score le plus élevé
            $maxScore = max($traitScores);
            $dominantTraits = array_keys($traitScores, $maxScore);

            // En cas d'égalité, choisir un au hasard
            $dominantTrait = $dominantTraits[array_rand($dominantTraits)];

            // Ajouter à son groupe
            if (isset($groupedByDominant[$dominantTrait])) {
                $groupedByDominant[$dominantTrait][] = $question;
            }
        }

        $finalQuestions = [];

        foreach ($groupedByDominant as $trait => $questions) {
            if (count($questions) < 7) {
                throw new \RuntimeException("Pas assez de questions pour le trait {$trait}");
            }

            // Sélectionner 7 questions aléatoirement
            $keys = array_rand($questions, 7);
            if (!is_array($keys)) {
                $keys = [$keys];
            }

            foreach ($keys as $key) {
                $finalQuestions[] = $questions[$key];
            }
        }

        // Mélanger le tout pour l'ordre
        shuffle($finalQuestions);

        return $finalQuestions;
    }
}
