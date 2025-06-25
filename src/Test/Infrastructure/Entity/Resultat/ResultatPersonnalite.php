<?php

namespace App\Test\Infrastructure\Entity\Resultat;

use App\Test\Infrastructure\Entity\TestPersonality;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class ResultatPersonnalite extends ResultatTest
{

    #[ORM\Column(type: "json")]
    private array $scores = [];



    public function __construct(TestPersonality $test, array $scores)
    {
        parent::__construct($test);
        $this->scores = $scores;
    }

    public function getScores(): array
    {
        return $this->scores;
    }

    public function setScores(array $scores): self
    {
        $this->scores = $scores;
        return $this;
    }
}