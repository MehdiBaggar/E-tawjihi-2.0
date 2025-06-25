<?php

namespace App\Test\Infrastructure\Repository;

use App\Test\Domain\Repository\TestPersonalityRepositoryInterface;
use App\Test\Domain\Entity\PersonalityTest as DomainPersonalityTest;
use App\Test\Infrastructure\Entity\Question\QuestionPersonality;
use App\Test\Infrastructure\Entity\TestPersonality as InfraTestPersonality;
use App\Test\Infrastructure\Mapper\TestPersonalityMapper;
use App\User\Infrastructure\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class TestPersonalityRepository implements TestPersonalityRepositoryInterface
{
    public function __construct(private EntityManagerInterface $em) {}

    public function findById(int $id): ?DomainPersonalityTest
    {
        /** @var InfraTestPersonality|null $entity */
        $entity = $this->em->getRepository(InfraTestPersonality::class)->find($id);

        if (!$entity) {
            return null;
        }

        return TestPersonalityMapper::toDomain($entity);
    }

    public function save(DomainPersonalityTest $test): void
    {
        $entity = $this->getOrCreateEntity($test);

        $this->attachUser($entity, $test);

        $this->em->persist($entity);
        $this->em->flush();

        if ($test->getId() === null) {
            $test->setId($entity->getId());
        }
    }


    public function findByUserId(int $userId): ?DomainPersonalityTest
    {
        $entity = $this->em->getRepository(InfraTestPersonality::class)
            ->createQueryBuilder('pt')
            ->join('pt.user', 'u')
            ->andWhere('u.id = :userId')
            ->setParameter('userId', $userId)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        if (!$entity) {
            return null;
        }

        return TestPersonalityMapper::toDomain($entity);
    }
    private function getOrCreateEntity(DomainPersonalityTest $test): InfraTestPersonality
    {
        $entity = null;
        if ($test->getId() !== null) {
            $entity = $this->em->getRepository(InfraTestPersonality::class)->find($test->getId());
        }

        if (!$entity) {
            $entity = TestPersonalityMapper::toInfrastructure($test);
        } else {
            $entity->setTitle($test->getTitle());
            $entity->setDescription($test->getDescription());
            $entity->setIsActive($test->isActive());
            $entity->setEstimatedDuration($test->getEstimatedDuration());
            $entity->setOrder($test->getOrder());
        }

        $this->syncQuestions($entity, $test);

        return $entity;
    }

    private function syncQuestions(InfraTestPersonality $entity, DomainPersonalityTest $test): void
    {
        $entity->clearQuestions();

        foreach ($test->getQuestions() as $domainQuestion) {
            $infraQuestion = $this->em->getRepository(QuestionPersonality::class)->find($domainQuestion->getId());

            if ($infraQuestion) {
                $entity->addQuestion($infraQuestion);
            } else {
                throw new \RuntimeException("Question with ID {$domainQuestion->getId()} not found in DB.");
            }
        }
    }

    private function attachUser(InfraTestPersonality $entity, DomainPersonalityTest $test): void
    {
        $domainUser = $test->getUser();

        if ($domainUser === null) {
            throw new \RuntimeException("Le test n'a pas d'utilisateur associé.");
        }

        $doctrineUser = $this->em->getRepository(User::class)->find($domainUser->getId());

        if ($doctrineUser === null) {
            throw new \RuntimeException("Utilisateur introuvable en base pour l'id " . $domainUser->getId());
        }

        $entity->setUser($doctrineUser);
    }
    public function hasUserCompletedPersonalityTest(User $user): bool
    {
        return $this->findByUserId($user->getId()) !== null;
    }





}
