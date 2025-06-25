<?php

namespace App\Test\Domain\Entity\Resultat;


use App\Test\Domain\Entity\AbstractTest;

class ResultatPersonnalite extends ResultatTest
{
    /**
     * Résultat stocké sous forme de tableau associatif, ex : ['REALISTIC' => 10, 'INVESTIGATIVE' => 5, ...]
     *
     * @var array<string,int>
     */
    private array $scores;

    public function __construct(AbstractTest $test, array $scores)
    {
        parent::__construct($test);
        $this->scores = $scores;
    }

    public function getScores(): array
    {
        return $this->scores;
    }
}