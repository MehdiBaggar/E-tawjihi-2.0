<?php

namespace App\Test\Infrastructure\Entity\Resultat;

use App\Test\Infrastructure\Entity\AbstractTest;
use App\Test\Infrastructure\Entity\TestPersonality;
use Doctrine\ORM\Mapping as ORM;
use DateTimeImmutable;

#[ORM\Entity]
#[ORM\Table(name: "resultats_tests")]
#[ORM\InheritanceType("SINGLE_TABLE")]
#[ORM\DiscriminatorColumn(name: "result_type", type: "string")]
#[ORM\DiscriminatorMap([
    "personnalite" => ResultatPersonnalite::class,
    // ajouter d'autres types de résultats ici
])]
abstract class ResultatTest
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    protected ?int $id = null;

    #[ORM\ManyToOne(targetEntity: AbstractTest::class)]
    #[ORM\JoinColumn(name: "test_id", referencedColumnName: "id", nullable: false)]
    protected ?AbstractTest $test = null;

    #[ORM\Column(type: "datetime_immutable", nullable: true)]
    protected ?DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: "json", nullable: true)]
    private ?array $rawAnswers = null;


    public function __construct(?TestPersonality $test = null)
    {
        $this->test = $test;
        $this->createdAt = new DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTest(): ?AbstractTest
    {
        return $this->test;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }
    public function setTest(?AbstractTest $test): self
    {
        $this->test = $test;
        return $this;
    }
    public function getRawAnswers(): ?array
    {
        return $this->rawAnswers;
    }

    public function setRawAnswers(?array $rawAnswers): self
    {
        $this->rawAnswers = $rawAnswers;
        return $this;
    }

}
