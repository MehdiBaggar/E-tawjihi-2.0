<?php
namespace App\Test\Infrastructure\Entity\Question;

use App\Test\Infrastructure\Repository\QuestionDoctrineRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Test\Infrastructure\Entity\AbstractTest;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;


#[ORM\Entity(repositoryClass: QuestionDoctrineRepository::class)]
#[ORM\Table(name: "questions")]
#[ORM\InheritanceType("SINGLE_TABLE")]
#[ORM\DiscriminatorColumn(name: "question_type", type: "string")]
#[ORM\DiscriminatorMap([
    "personality" => QuestionPersonality::class,
])]
abstract class AbstractQuestion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    protected ?int $id = null;

    #[ORM\Column(type: "text")]
    protected string $text;




    // Getters and setters for id and text...
    public function getId(): ?int { return $this->id; }
    public function getText(): string { return $this->text; }
    public function __toString(): string
    {
        return $this->id . ' - ' . $this->getId();
    }

}