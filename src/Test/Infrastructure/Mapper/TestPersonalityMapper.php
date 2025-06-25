<?php
namespace App\Test\Infrastructure\Mapper;

use App\Test\Domain\Entity\PersonalityTest as DomainPersonalityTest;
use App\Test\Domain\Factory\PersonalityTestFactory;
use App\Test\Infrastructure\Entity\TestPersonality as InfraTestPersonality;
use App\Test\Infrastructure\Mapper\QuestionMapper;

class TestPersonalityMapper
{
    public static function toDomain(InfraTestPersonality $entity): DomainPersonalityTest
    {
        $questions = array_map(fn($q) => QuestionMapper::toDomain($q), $entity->getQuestions());

        return PersonalityTestFactory::create(
            $entity->getId(),
            $entity->getTitle(),
            $entity->getDescription(),
            $entity->isActive(),
            $entity->getEstimatedDuration(),
            $entity->getOrder(),
            $questions,
            $entity->getCreatedAt(),
            $entity->getUpdatedAt()
        );
    }

    public static function toInfrastructure(DomainPersonalityTest $domain): InfraTestPersonality
    {
        $entity = new InfraTestPersonality();

        $entity->setTitle($domain->getTitle());
        $entity->setDescription($domain->getDescription());
        $entity->setIsActive($domain->isActive());
        $entity->setEstimatedDuration($domain->getEstimatedDuration());
        $entity->setOrder($domain->getOrder());


        return $entity;
    }
}
