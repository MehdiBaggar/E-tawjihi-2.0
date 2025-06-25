<?php
namespace App\Test\Infrastructure\Entity\Question;

use App\Test\Domain\ValueObject\Enum\RIASECType;
use App\Test\Infrastructure\Entity\TestPersonality;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class QuestionPersonality extends AbstractQuestion
{

    /**
     * Tableau associatif ['R' => 3, 'I' => 0, ...]
     * Stocké en JSON dans la base.
     */
    #[ORM\Column(type: "json")]
    private array $traitScores = [];





    public function getTraitScores(): array
    {
        return $this->traitScores;
    }

    /**
     * Met à jour le score d'un trait particulier
     */
    public function setTraitScore(string $trait, int $score): self
    {
        if (!array_key_exists($trait, $this->traitScores)) {
            throw new \InvalidArgumentException("Trait de personnalité invalide : $trait");
        }
        $this->traitScores[$trait] = $score;
        return $this;
    }

    /**
     * Récupère le score d'un trait spécifique, ou 0 si inexistant
     */
    public function getTraitScore(string $trait): int
    {
        return $this->traitScores[$trait] ?? 0;
    }
    public function setTraitScores(array $traitScores): self
    {
        $this->traitScores = $traitScores;
        return $this;
    }
    public function setTest(?TestPersonality $test): self
    {
        $this->test = $test;
        return $this;
    }
    public function setText(string $text): self
    {
        $this->text = $text;
        return $this;
    }


}
