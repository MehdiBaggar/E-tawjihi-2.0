<?php
namespace App\Test\Infrastructure\Mapper;

use App\Test\Domain\Entity\Question\AbstractQuestion;
use App\Test\Domain\Entity\Question\QuestionPersonality;
use App\Test\Infrastructure\Entity\Question\AbstractQuestion as InfraAbstractQuestion;
use App\Test\Infrastructure\Entity\Question\QuestionPersonality as InfraQuestionPersonality;

class QuestionMapper
{
    public static function toDomain(InfraAbstractQuestion $entity): AbstractQuestion
    {
        // Ici, on check le type concret
        if ($entity instanceof InfraQuestionPersonality) {
            $domainQuestion = new QuestionPersonality(
                $entity->getId(),
                $entity->getText(),
                $entity->getTraitScores()
            );

            // plus setId, etc. si nécessaire
            return $domainQuestion;
        }

        // ... autres types de questions si besoin
        throw new \InvalidArgumentException('Type de question inconnu.');
    }

    public static function toInfrastructure(AbstractQuestion $domain): InfraAbstractQuestion
    {
        if ($domain instanceof QuestionPersonality) {
            $entity = new InfraQuestionPersonality();
            $entity->setText($domain->getText());
            $entity->setTraitScores($domain->getTraitScores());
            return $entity;
        }

        throw new \InvalidArgumentException('Type de question inconnu.');
    }
}
