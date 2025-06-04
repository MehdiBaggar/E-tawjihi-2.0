<?php

namespace App\User\Domain\ValueObject;

class AcademicInfo
{
    public function __construct(
        private ?string $institutionType,
        private ?string $currentLevel,
        private ?string $field,
        private ?float $average = null,
        private ?int $baccalaureateYear = null,
        private ?string $lycee,
        private ?string $nomTuteur,
        private ?string $telTuteur

    ) {}

    public function getInstitutionType(): ?string
    {
        return $this->institutionType;
    }

    public function getCurrentLevel(): ?string
    {
        return $this->currentLevel;
    }

    public function getField(): ?string
    {
        return $this->field;
    }

    public function getAverage(): ?float
    {
        return $this->average;
    }

    public function getBaccalaureateYear(): ?int
    {
        return $this->baccalaureateYear;
    }
    public function getLycee(): ?string
    {
        return $this->lycee;
    }
    public function getNomTuteur(): ?string
    {
        return $this->nomTuteur;
    }
    public function getTelTuteur(): ?string
    {
        return $this->telTuteur;
    }
}
