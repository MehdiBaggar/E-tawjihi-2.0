<?php

namespace App\Test\Application\Service\Test;

use App\Test\Application\Service\Question\QuestionService;
use App\Test\Domain\Entity\AbstractTest;
use App\Test\Domain\Entity\PersonalityTest;
use App\Test\Application\Service\Test\TestServiceInterface;
use App\Test\Domain\Factory\PersonalityTestFactory;
use App\Test\Domain\Repository\TestPersonalityRepositoryInterface;
use App\Test\Domain\Repository\QuestionRepositoryInterface;
use App\User\Domain\Entity\User;
use Psr\Log\LoggerInterface;
use App\Test\Domain\Repository\PersonalityTestResultRepositoryInterface;



class PersonalityTestService implements TestServiceInterface
{
    private const PERSONALITY_TEST_LABEL = 'Test de Personnalité';
    public function __construct(
        private readonly QuestionService $questionService,
        private readonly TestPersonalityRepositoryInterface $testPersonalityRepository,
        private readonly QuestionRepositoryInterface $questionRepository,
        private readonly LoggerInterface $logger,
        private readonly PersonalityTestResultRepositoryInterface $resultRepository,

    ) {}

    public function createPersonalityTest(): PersonalityTest
    {
        $this->logger->info('Creating a new PersonalityTest...');

        $questions = $this->questionService->loadBalancedQuestions();

        if (empty($questions)) {
            $this->logger->error('No questions loaded for the test.');
            throw new \RuntimeException("Aucune question chargée !");
        }

        $this->logger->debug('Questions loaded for the test.', ['count' => count($questions)]);

        $test = PersonalityTestFactory::create(
            null,
            self::PERSONALITY_TEST_LABEL,
            self::PERSONALITY_TEST_LABEL,
            true,
            15,
            0,
            $questions
        );

        if (!$test instanceof PersonalityTest) {
            $this->logger->critical('Factory failed to produce a PersonalityTest.');
            throw new \RuntimeException("La factory n'a pas créé un PersonalityTest");
        }

        try {
            $this->testPersonalityRepository->save($test);
            $this->logger->info('PersonalityTest successfully saved.');
        } catch (\Throwable $e) {
            $this->logger->error("Error saving the test", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw new \RuntimeException("Erreur lors de la sauvegarde du test : " . $e->getMessage());
        }

        return $test;
    }

    public function loadTest(int $testId): ?AbstractTest
    {
        $this->logger->info("Loading test with ID: {$testId}");
        return $this->testPersonalityRepository->findById($testId);
    }
    public function getOrCreatePersonalityTestForUser(User $user): PersonalityTest
    {
        $existingTest = $this->testPersonalityRepository->findByUserId($user->getId());

        if ($existingTest !== null) {
            $this->logger->info('Existing PersonalityTest found for user id ' . $user->getId());
            return $existingTest;
        }

        // No test for user, create new one
        $this->logger->info('Creating new PersonalityTest for user id ' . $user->getId());

        $questions = $this->questionService->loadBalancedQuestions();

        if (empty($questions)) {
            $this->logger->error('No questions loaded for the test.');
            throw new \RuntimeException("Aucune question chargée !");
        }

        $test = PersonalityTestFactory::create(
            null,
            self::PERSONALITY_TEST_LABEL,
            self::PERSONALITY_TEST_LABEL,
            true,
            15,
            0,
            $questions
        );

        $test->setUser($user);

        $this->testPersonalityRepository->save($test);

        return $test;
    }


    public function evaluate(array $answers): array
    {
        $this->logger->info('Starting evaluation of answers.', ['answers' => $answers]);

        $results = [
            'R' => 0, 'I' => 0, 'A' => 0,
            'S' => 0, 'E' => 0, 'C' => 0,
        ];

        $questionIds = array_keys($answers);

        if (empty($questionIds)) {
            $this->logger->warning('No answers provided. Returning default zero results.');
            return $results;
        }

        $questions = array_map(
            fn($id) => $this->questionRepository->findById((int)$id),
            $questionIds
        );

        $questions = array_filter($questions); // Remove nulls

        $this->logger->debug('Fetched questions from repository.', ['count' => count($questions)]);

        foreach ($questions as $question) {
            $questionId = $question->getId();

            if (!isset($answers[$questionId])) {
                $this->logger->warning("Answer missing for question ID {$questionId}, skipping.");
                continue;
            }

            $answerValue = $answers[$questionId];
            $traitScores = $question->getTraitScores();

            $this->logger->debug("Evaluating question ID {$questionId}.", [
                'answerValue' => $answerValue,
                'traitScores' => $traitScores,
            ]);

            foreach ($results as $trait => $_) {
                $weight = $traitScores[$trait] ?? 0;
                $increment = $weight * $answerValue;
                $results[$trait] += $increment;

                $this->logger->debug("Trait {$trait} += {$increment} (weight: {$weight} * value: {$answerValue})");
            }
        }

        $this->logger->info('Evaluation completed.', ['results' => $results]);

        return $results;
    }

    public function interpretResults(mixed $result): array
    {
        if (!is_array($result) || empty($result)) {
            $this->logger->error("Invalid or empty results passed to interpretResults.");
            return [];
        }

        $totalScore = array_sum($result);
        if ($totalScore == 0) {
            return array_map(fn($score) => 0, $result);
        }

        $percentages = [];
        foreach ($result as $trait => $score) {
            $percentages[$trait] = round(($score / $totalScore) * 100, 2);
        }

        arsort($percentages);
        return $percentages;
    }
    public function savePersonalityTestResults(PersonalityTest $test, User $user, array $results, array $rawAnswers): void
    {
        $this->logger->info('Saving personality test results.', [
            'testId' => $test->getId(),
            'userId' => $user->getId(),
            'results' => $results,
            'rawAnswers' => $rawAnswers,
        ]);

        try {
            $this->resultRepository->saveResults($test->getId(), $results, $rawAnswers);
            $this->logger->info('Results successfully saved.');
        } catch (\Throwable $e) {
            $this->logger->error('Failed to save personality test results.', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw new \RuntimeException("Erreur lors de la sauvegarde des résultats : " . $e->getMessage());
        }
    }

}
