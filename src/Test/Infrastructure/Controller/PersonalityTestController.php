<?php

namespace App\Test\Infrastructure\Controller;

use App\Test\Domain\Repository\PersonalityTestResultRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Test\Application\Service\Test\PersonalityTestService;
use App\User\Domain\Entity\User as DomainUser;
use App\User\Domain\Repository\UserRepositoryInterface;
use App\User\Infrastructure\Entity\User as DoctrineUser;
use Psr\Log\LoggerInterface;

class PersonalityTestController extends AbstractController
{
    private const ERROR_USER_NOT_AUTHENTICATED = 'User not authenticated';
    private const ERROR_USER_DOMAIN_NOT_FOUND = 'User domain not found';
    public function __construct(
        private PersonalityTestService $personalityTestService,
        private UserRepositoryInterface $userRepository,
        private readonly LoggerInterface $logger,
        private readonly PersonalityTestResultRepositoryInterface $resultRepository,
    ) {}

    #[Route('/api/test/Personality/questions', name: 'get_or_create_personality_test', methods: ['GET'])]
    public function getOrCreateTest(Request $request): JsonResponse
    {
        /** @var DoctrineUser|null $doctrineUser */
        $doctrineUser = $this->getUser();

        if (!$doctrineUser instanceof DoctrineUser) {
            return $this->json(['error' => self::ERROR_USER_NOT_AUTHENTICATED], 401);
        }

        // Fetch the domain user via repository using doctrineUser id
        $domainUser = $this->userRepository->findById($doctrineUser->getId());

        if (!$domainUser instanceof DomainUser) {
            $this->logger->warning('Domain user not found for Doctrine user ID: ' . $doctrineUser->getId());
            return $this->json(['error' => self::ERROR_USER_DOMAIN_NOT_FOUND], 404);
        }

        try {
            $test = $this->personalityTestService->getOrCreatePersonalityTestForUser($domainUser);

            if (!$test) {
                $this->logger->error('Service returned null test.');
                return $this->json(['error' => 'Test not found'], 500);
            }

            $questions = [];
            foreach ($test->getQuestions() as $question) {
                $questions[] = [
                    'id' => $question->getId(),
                    'text' => $question->getText(),
                ];
            }


            $results = $this->resultRepository->findResultsByTest($test->getId());
            $rawAnswers = $this->resultRepository->findRawAnswersByTest($test->getId());

            return $this->json([
                'testId' => $test->getId(),
                'title' => $test->getTitle(),
                'questions' => $questions,
                'previousRawAnswers' => $rawAnswers,
            ]);
        } catch (\Throwable $e) {
            $this->logger->error('Error in getOrCreateTest: ' . $e->getMessage());
            return $this->json(['error' => 'Internal server error'], 500);
        }
    }

    #[Route('/api/test/personality/submit', name: 'submit_personality_test', methods: ['POST'])]
    public function submit(Request $request): JsonResponse
    {
        $doctrineUser = $this->getUser();

        if (!$doctrineUser instanceof DoctrineUser) {
            return $this->json(['error' => self::ERROR_USER_NOT_AUTHENTICATED], 401);
        }

        $domainUser = $this->userRepository->findById($doctrineUser->getId());

        if (!$domainUser instanceof DomainUser) {
            return $this->json(['error' => self::ERROR_USER_DOMAIN_NOT_FOUND], 404);
        }

        $data = json_decode($request->getContent(), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return $this->json(['error' => 'Invalid JSON payload'], 400);
        }

        if (!isset($data['answers']) || !is_array($data['answers'])) {
            return $this->json(['error' => 'Invalid or missing answers data'], 400);
        }

        $answers = $data['answers'];

        try {
            $results = $this->personalityTestService->evaluate($answers);
            $interpretation = $this->personalityTestService->interpretResults($results);

            $test = $this->personalityTestService->getOrCreatePersonalityTestForUser($domainUser);
            $this->personalityTestService->savePersonalityTestResults($test, $domainUser, $interpretation, $answers);

        } catch (\Throwable $e) {
            $this->logger->error('Error evaluating personality test answers', [
                'exception' => $e,
                'answers' => $answers,
            ]);

            return $this->json(['error' => 'Internal server error during test evaluation'], 500);
        }

        return $this->json([

        ]);
    }
    #[Route('/api/test/personality/results', name: 'get_personality_test_results', methods: ['GET'])]
    public function getResults(Request $request): JsonResponse
    {

        $doctrineUser = $this->getUser();

        if (!$doctrineUser instanceof DoctrineUser) {
            return $this->json(['error' => self::ERROR_USER_NOT_AUTHENTICATED], 401);
        }

        $domainUser = $this->userRepository->findById($doctrineUser->getId());

        if (!$domainUser instanceof DomainUser) {
            return $this->json(['error' => self::ERROR_USER_DOMAIN_NOT_FOUND], 404);
        }

        try {
            $test = $this->personalityTestService->getOrCreatePersonalityTestForUser($domainUser);

            if (!$test) {
                return $this->json(['error' => 'Test not found'], 404);
            }

            $scores = $this->resultRepository->findResultsByTest($test->getId());

            if (!$scores) {
                return $this->json(['error' => 'Results not found'], 404);
            }

            return $this->json([
                'testId' => $test->getId(),
                'scores' => $scores,
            ]);
        } catch (\Throwable $e) {
            $this->logger->error('Error fetching test scores: ' . $e->getMessage());
            return $this->json(['error' => 'Internal server error'], 500);
        }
    }
}
