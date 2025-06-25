<?php

namespace App\Test\Domain\Entity\Resultat;

use App\Test\Domain\Entity\AbstractTest;
use App\User\Domain\Entity\User;
use DateTimeImmutable;

abstract class ResultatTest
{
    protected ?int $id = null;

    protected AbstractTest $test;


    protected DateTimeImmutable $createdAt;

    public function __construct(AbstractTest $test)
    {
        $this->test = $test;
        $this->createdAt = new DateTimeImmutable();
    }

    // Getters

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTest(): AbstractTest
    {
        return $this->test;
    }



    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    // Setters

    public function setTest(AbstractTest $test): self
    {
        $this->test = $test;
        return $this;
    }

}
