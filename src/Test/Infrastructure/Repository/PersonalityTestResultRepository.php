<?php

namespace App\Test\Infrastructure\Repository;

use App\Test\Domain\Repository\PersonalityTestResultRepositoryInterface;
use App\Test\Infrastructure\Entity\Resultat\ResultatPersonnalite;
use App\Test\Infrastructure\Entity\TestPersonality;
use Doctrine\ORM\EntityManagerInterface;

class PersonalityTestResultRepository implements PersonalityTestResultRepositoryInterface
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function findResultsByTest(int $testId): ?array
    {
        $repo = $this->em->getRepository(ResultatPersonnalite::class);

        $result = $repo->findOneBy([
            'test' => $testId,
        ]);

        if (!$result) {
            return null;
        }

        return $result->getScores();
    }

    public function saveResults(int $testId, array $results, array $rawAnswers = []): void
    {
        $repo = $this->em->getRepository(ResultatPersonnalite::class);

        $existing = $repo->findOneBy([
            'test' => $testId,
        ]);

        if (!$existing) {
            $test = $this->em->getReference(TestPersonality::class, $testId);
            $resultEntity = new ResultatPersonnalite($test, $results);
            $resultEntity->setRawAnswers($rawAnswers);
            $this->em->persist($resultEntity);
        } else {
            $existing->setScores($results);
            $existing->setRawAnswers($rawAnswers);
        }


        $this->em->flush();
    }
    public function findRawAnswersByTest(int $testId): ?array
    {
        $repo = $this->em->getRepository(ResultatPersonnalite::class);

        $result = $repo->findOneBy([
            'test' => $testId,
        ]);

        if (!$result) {
            return null;
        }

        return $result->getRawAnswers();
    }
}
