<?php

namespace App\Tests\Test\Application\Service;

use App\Test\Application\Service\Question\QuestionService;
use App\Test\Domain\Entity\Question\QuestionPersonality;
use App\Test\Domain\Repository\QuestionPersonalityRepositoryInterface;
use PHPUnit\Framework\TestCase;

class QuestionServiceTest extends TestCase
{
    private $repository;
    private QuestionService $service;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(QuestionPersonalityRepositoryInterface::class);
        $this->service = new QuestionService($this->repository);
    }

    public function testLoadRandomQuestionsReturnsAllIfNotEnough()
    {
        $questions = [
            $this->createMock(QuestionPersonality::class),
            $this->createMock(QuestionPersonality::class),
        ];

        $this->repository->method('findAll')->willReturn($questions);

        $result = $this->service->loadRandomQuestions(5);

        $this->assertCount(2, $result);
        $this->assertEquals($questions, $result);
    }

    public function testLoadRandomQuestionsReturnsExactCount()
    {
        $questions = [];
        for ($i = 0; $i < 10; $i++) {
            $questions[] = $this->createMock(QuestionPersonality::class);
        }

        $this->repository->method('findAll')->willReturn($questions);

        $result = $this->service->loadRandomQuestions(5);

        $this->assertCount(5, $result);
        foreach ($result as $q) {
            $this->assertInstanceOf(QuestionPersonality::class, $q);
        }
    }

    public function testLoadBalancedQuestionsReturns42()
    {
        $questions = [];

        foreach (['R','I','A','S','E','C'] as $trait) {
            for ($i = 0; $i < 10; $i++) {
                $q = $this->createMock(QuestionPersonality::class);
                $q->method('getTraitScores')->willReturn([
                    'R' => $trait === 'R' ? 3 : 1,
                    'I' => $trait === 'I' ? 3 : 1,
                    'A' => $trait === 'A' ? 3 : 1,
                    'S' => $trait === 'S' ? 3 : 1,
                    'E' => $trait === 'E' ? 3 : 1,
                    'C' => $trait === 'C' ? 3 : 1,
                ]);
                $questions[] = $q;
            }
        }

        $this->repository->method('findAll')->willReturn($questions);

        $result = $this->service->loadBalancedQuestions();

        $this->assertCount(42, $result); // 6 traits * 7
        foreach ($result as $q) {
            $this->assertInstanceOf(QuestionPersonality::class, $q);
        }
    }

    public function testLoadBalancedQuestionsThrowsIfNotEnough()
    {
        $questions = [];

        foreach (['R','I','A','S','E','C'] as $trait) {
            for ($i = 0; $i < 6; $i++) {
                $q = $this->createMock(QuestionPersonality::class);
                $q->method('getTraitScores')->willReturn([
                    'R' => $trait === 'R' ? 3 : 1,
                    'I' => $trait === 'I' ? 3 : 1,
                    'A' => $trait === 'A' ? 3 : 1,
                    'S' => $trait === 'S' ? 3 : 1,
                    'E' => $trait === 'E' ? 3 : 1,
                    'C' => $trait === 'C' ? 3 : 1,
                ]);
                $questions[] = $q;
            }
        }

        $this->repository->method('findAll')->willReturn($questions);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Pas assez de questions pour le trait');

        $this->service->loadBalancedQuestions();
    }
}
