<?php

namespace App\Test\Infrastructure\Repository;

use App\Test\Domain\Entity\Question\AbstractQuestion;
use App\Test\Domain\Repository\QuestionRepositoryInterface;
use App\Test\Infrastructure\Entity\Question\QuestionPersonality as InfraQuestionPersonality;
use App\Test\Infrastructure\Mapper\QuestionMapper;
use Doctrine\ORM\EntityManagerInterface;

class QuestionDoctrineRepository implements QuestionRepositoryInterface
{
    public function __construct(private EntityManagerInterface $em) {}

    public function findAll(): array
    {
        $entities = $this->em->getRepository(InfraQuestionPersonality::class)->findAll();
        return array_map(fn($e) => QuestionMapper::toDomain($e), $entities);
    }

    public function findById(int $id): ?AbstractQuestion
    {
        $entity = $this->em->getRepository(InfraQuestionPersonality::class)->find($id);
        return $entity ? QuestionMapper::toDomain($entity) : null;
    }

    public function add(AbstractQuestion $question): void
    {
        $entity = QuestionMapper::toInfrastructure($question);
        $this->em->persist($entity);
        $this->em->flush();
    }

    public function update(AbstractQuestion $question): void
    {
        $entity = $this->em->getRepository(InfraQuestionPersonality::class)->find($question->getId());

        if (!$entity) {
            throw new \RuntimeException("Question not found for update");
        }

        $entity->setText($question->getText());
        $entity->setTraitScores($question->getTraitScores());

        $this->em->flush();
    }

    public function delete(int $id): void
    {
        $entity = $this->em->getRepository(InfraQuestionPersonality::class)->find($id);
        if ($entity) {
            $this->em->remove($entity);
            $this->em->flush();
        }
    }
}
