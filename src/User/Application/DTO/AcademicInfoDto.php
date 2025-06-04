<?php

namespace App\User\Application\DTO;

class AcademicInfoDto
{
    public ?string $institutionType;
    public ?string $currentLevel;
    public ?string $field;
    public ?float $average;
    public ?int $baccalaureateYear;
    public ?string $lycee;
    public ?string $nomTuteur;
    public ?string $telTuteur;


    public function __construct(
        ?string $institutionType = null,
        ?string $currentLevel = null,
        ?string $field = null,
        ?float $average = null,
        ?int $baccalaureateYear = null,
        ?string $lycee,
        ?string $nomTuteur,
        ?string $telTuteur

    ) {
        $this->institutionType = $institutionType;
        $this->currentLevel = $currentLevel;
        $this->field = $field;
        $this->average = $average;
        $this->baccalaureateYear = $baccalaureateYear;
        $this->lycee = $lycee;
        $this->nomTuteur = $nomTuteur;
        $this->telTuteur = $telTuteur;
    }

}
