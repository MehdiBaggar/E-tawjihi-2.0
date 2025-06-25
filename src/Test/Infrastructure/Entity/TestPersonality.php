<?php

namespace App\Test\Infrastructure\Entity;

use App\Test\Infrastructure\Entity\Question\QuestionPersonality;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Test\Infrastructure\Entity\Question\AbstractQuestion;


#[ORM\Entity]
class TestPersonality extends AbstractTest
{


    public function __construct()
    {
        parent::__construct();
        $this->type = 'personnalite';
    }


    public function getQuestions(): array
    {
        return $this->questions->toArray();
    }
    public function addQuestion(AbstractQuestion $question): void
    {
        if (!$question instanceof QuestionPersonality) {
            throw new \InvalidArgumentException("Seules les questions de type QuestionPersonality sont autorisées.");
        }

        if (!$this->questions->contains($question)) {
            $this->questions->add($question);
            $question->setTest($this);
        }
    }
    public function clearQuestions(): void
    {
        $this->questions->clear();
    }





}
