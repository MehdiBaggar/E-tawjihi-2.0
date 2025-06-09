<?php

namespace App\Test\Application\Service\Question;

use App\Test\Application\DTO\Question\QuestionPersonalityDTO;
use App\Test\Domain\Repository\QuestionRepositoryInterface;

class QuestionFetcherService
{
    private QuestionRepositoryInterface $repository;

    public function __construct(QuestionRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return QuestionPersonalityDTO[]
     */
    public function getQuestionsForPersonalityTest(): array
    {
        $questions = $this->repository->findAll();

        return array_map(
            fn($question) => QuestionPersonalityDTO::fromEntity($question),
            $questions
        );
    }
}
