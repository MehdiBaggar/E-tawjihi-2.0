<?php
namespace App\Test\Infrastructure\Mapper;

use App\Test\Domain\Entity\Resultat\ResultatPersonnalite as DomainResult;
use App\Test\Infrastructure\Entity\Resultat\ResultatPersonnalite as DoctrineResult;
use App\Test\Infrastructure\Mapper\TestPersonalityMapper;

class ResultatPersonnaliteMapper
{
    public static function toDomain(DoctrineResult $entity): DomainResult
    {
        return new DomainResult(
            TestPersonalityMapper::toDomain($entity->getTest()),
            $entity->getScores()
        );
    }

    public static function toDoctrine(DomainResult $domain, ?DoctrineResult $entity = null): DoctrineResult
    {
        $testEntity = TestPersonalityMapper::toInfrastructure($domain->getTest());
        $scores = $domain->getScores();

        if (!$entity) {
            $entity = new DoctrineResult($testEntity, $scores);
        } else {
            $entity->setTest($testEntity);
            $entity->setScores($scores);
        }

        return $entity;
    }

}
