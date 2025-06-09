<?php
namespace App\Test\Domain\Repository;

use App\Test\Domain\Entity\Question\AbstractQuestion;

interface QuestionRepositoryInterface
{
    /**
     * @return AbstractQuestion[]
     */
    public function findAll(): array;

    public function findById(int $id): ?AbstractQuestion;

    public function add(AbstractQuestion $question): void;

    public function update(AbstractQuestion $question): void;

    public function delete(int $id): void;
}
