<?php

namespace App\User\Infrastructure\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'academic_info')]
class AcademicInfo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string',nullable: true, length: 100)]
    private ?string $typeEtablissement; // public, privé, mission, etc.

    #[ORM\Column(type: 'string',nullable: true, length: 50)]
    private ?string $niveauEtudes; // Terminale, Bac+1, etc.

    #[ORM\Column(type: 'string',nullable: true, length: 100,)]
    private ?string $filiere; // Filière : sciences, lettres…

    #[ORM\Column(type: 'float', nullable: true)]
    private ?float $moyenneGenerale = null; // Moyenne (optionnelle)

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $anneeObtentionBac = null;

    #[ORM\Column(type: 'string', length: 150, nullable: true)]
    private ?string $lycee = null;

    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    private ?string $nomTuteur = null;

    #[ORM\Column(type: 'string', length: 20, nullable: true)]
    private ?string $telTuteur = null;


    #[ORM\OneToOne(inversedBy: 'academicInfo', targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private User $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTypeEtablissement(): ?string
    {
        return $this->typeEtablissement;
    }

    public function setTypeEtablissement(?string $institutionType): void
    {
        $this->typeEtablissement = $institutionType;
    }

    public function getNiveauEtudes(): ?string
    {
        return $this->niveauEtudes;
    }

    public function setNiveauEtudes(?string $currentLevel): void
    {
        $this->niveauEtudes = $currentLevel;
    }

    public function getFiliere(): ?string
    {
        return $this->filiere;
    }

    public function setFiliere(?string $field): void
    {
        $this->filiere = $field;
    }

    public function getMoyenneGenerale(): ?float
    {
        return $this->moyenneGenerale;
    }

    public function setMoyenneGenerale(?float $average): void
    {
        $this->moyenneGenerale = $average;
    }

    public function getAnneeObtentionBac(): ?int
    {
        return $this->anneeObtentionBac;
    }

    public function setAnneeObtentionBac(?int $year): void
    {
        $this->anneeObtentionBac = $year;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function getLycee(): ?string
    {
        return $this->lycee;
    }

    public function setLycee(?string $lycee): void
    {
        $this->lycee = $lycee;
    }

    public function getNomTuteur(): ?string
    {
        return $this->nomTuteur;
    }

    public function setNomTuteur(?string $nomTuteur): void
    {
        $this->nomTuteur = $nomTuteur;
    }

    public function getTelTuteur(): ?string
    {
        return $this->telTuteur;
    }

    public function setTelTuteur(?string $telTuteur): void
    {
        $this->telTuteur = $telTuteur;
    }
}
