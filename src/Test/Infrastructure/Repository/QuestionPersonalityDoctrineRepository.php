<?php
namespace App\Test\Infrastructure\Repository;

use App\Test\Domain\Entity\Question\AbstractQuestion;
use App\Test\Domain\Entity\Question\QuestionPersonality;
use App\Test\Domain\Repository\QuestionPersonalityRepositoryInterface;
use App\Test\Infrastructure\Entity\Question\QuestionPersonality as InfraQuestionPersonality;
use App\Test\Infrastructure\Mapper\QuestionMapper;
use Doctrine\ORM\EntityManagerInterface;

class QuestionPersonalityDoctrineRepository implements QuestionPersonalityRepositoryInterface
{
    public function __construct(private EntityManagerInterface $em) {}

    public function findAll(): array
    {
        $entities = $this->em->getRepository(InfraQuestionPersonality::class)->findAll();


        return array_map(fn(InfraQuestionPersonality $entity) => QuestionMapper::toDomain($entity), $entities);
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

        // Si tu veux synchroniser l’ID généré en base dans l’entité domaine,
        // tu peux faire un setId ici (à condition que ton domaine l’accepte).
        // $question->setId($entity->getId());
    }

    public function update(AbstractQuestion $question): void
    {
        // Soit tu récupères l'entité infrastructure existante et tu mets à jour ses propriétés,
        // soit tu assumes que le domaine et infrastructure sont synchronisés.

        // Exemple rapide (pas optimal) : on pourrait récupérer, mapper, flush
        $entity = $this->em->getRepository(InfraQuestionPersonality::class)->find($question->getId());

        if ($entity === null) {
            throw new \RuntimeException('Question non trouvée pour mise à jour');
        }

        $entity->setText($question->getText());
        $entity->setTraitScores($question->getTraitScores());

        $this->em->flush();
    }

    public function delete(int $id): void
    {
        $entity = $this->em->getRepository(InfraQuestionPersonality::class)->find($id);
        if ($entity !== null) {
            $this->em->remove($entity);
            $this->em->flush();
        }
    }

    public function findAllByCategory(string $riasecType): array
    {
        $entities = $this->em->getRepository(InfraQuestionPersonality::class)->findAll();

        $filtered = array_filter($entities, fn(InfraQuestionPersonality $entity) =>
        array_key_exists($riasecType, $entity->getTraitScores())
        );

        return array_map(fn(InfraQuestionPersonality $entity) => QuestionMapper::toDomain($entity), $filtered);
    }
}
