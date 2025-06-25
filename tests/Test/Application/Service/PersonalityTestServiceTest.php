<?php

namespace App\Tests\Test\Application\Service;

use App\Test\Application\Service\Test\PersonalityTestService;
use App\Test\Application\Service\Question\QuestionService;
use App\Test\Domain\Repository\TestPersonalityRepositoryInterface;
use App\Test\Domain\Repository\QuestionRepositoryInterface;
use App\Test\Domain\Repository\PersonalityTestResultRepositoryInterface;
use App\User\Domain\Entity\User;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use App\Test\Domain\Entity\PersonalityTest;
use App\Test\Domain\Entity\Question\QuestionPersonality;

class PersonalityTestServiceTest extends TestCase
{
    private $questionService;
    private $testRepository;
    private $questionRepository;
    private $logger;
    private $resultRepository;
    private $service;

    protected function setUp(): void
    {
        $this->questionService = $this->createMock(QuestionService::class);
        $this->testRepository = $this->createMock(TestPersonalityRepositoryInterface::class);
        $this->questionRepository = $this->createMock(QuestionRepositoryInterface::class);
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->resultRepository = $this->createMock(PersonalityTestResultRepositoryInterface::class);

        // Allow multiple calls to logger methods
        $this->logger->method('info');
        $this->logger->method('debug');
        $this->logger->method('error');
        $this->logger->method('warning');
        $this->logger->method('critical');

        $this->service = new PersonalityTestService(
            $this->questionService,
            $this->testRepository,
            $this->questionRepository,
            $this->logger,
            $this->resultRepository
        );
    }

    public function testCreatePersonalityTestSuccessful()
    {
        $questions = [
            new QuestionPersonality(1, "Question 1", ['R' => 1, 'I' => 0, 'A' => 0, 'S' => 0, 'E' => 0, 'C' => 0])
        ];

        $this->questionService->method('loadBalancedQuestions')->willReturn($questions);
        $this->testRepository->expects($this->once())->method('save');

        $test = $this->service->createPersonalityTest();

        $this->assertInstanceOf(PersonalityTest::class, $test);
        $this->assertEquals("Test de Personnalité", $test->getTitle());
        $this->assertCount(1, $test->getQuestions());
    }

    public function testCreatePersonalityTestThrowsWhenNoQuestions()
    {
        $this->questionService->method('loadBalancedQuestions')->willReturn([]);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage("Aucune question chargée !");

        $this->service->createPersonalityTest();
    }

    public function testEvaluateComputesCorrectly()
    {
        $answers = [1 => 2, 2 => 3];

        $q1 = $this->createMock(QuestionPersonality::class);
        $q1->method('getId')->willReturn(1);
        $q1->method('getTraitScores')->willReturn(['R' => 1, 'I' => 0, 'A' => 0, 'S' => 0, 'E' => 0, 'C' => 0]);

        $q2 = $this->createMock(QuestionPersonality::class);
        $q2->method('getId')->willReturn(2);
        $q2->method('getTraitScores')->willReturn(['R' => 0, 'I' => 1, 'A' => 0, 'S' => 0, 'E' => 0, 'C' => 0]);

        $this->questionRepository->method('findById')
            ->willReturnMap([
                [1, $q1],
                [2, $q2],
            ]);

        $results = $this->service->evaluate($answers);

        $this->assertEquals(2, $results['R']);
        $this->assertEquals(3, $results['I']);
        $this->assertEquals(0, $results['A']);
    }

    public function testInterpretResultsCalculatesPercentages()
    {
        $rawResults = ['R' => 2, 'I' => 3, 'A' => 5];
        $percentages = $this->service->interpretResults($rawResults);

        $this->assertEquals(20.0, $percentages['R']);
        $this->assertEquals(30.0, $percentages['I']);
        $this->assertEquals(50.0, $percentages['A']);
    }

    public function testInterpretResultsEmptyArrayReturnsEmpty()
    {
        $this->assertSame([], $this->service->interpretResults([]));
    }

    public function testSavePersonalityTestResultsCallsRepository()
    {
        $test = $this->createMock(PersonalityTest::class);
        $test->method('getId')->willReturn(123);

        $user = $this->createMock(User::class);
        $user->method('getId')->willReturn(456);

        $results = ['R' => 10];
        $rawAnswers = [1 => 5];

        $this->resultRepository->expects($this->once())
            ->method('saveResults')
            ->with(123, $results, $rawAnswers);

        // Allow multiple logger->info() calls
        $this->logger->method('info');

        $this->service->savePersonalityTestResults($test, $user, $results, $rawAnswers);
    }
}
