<?php

namespace App\Command;

use App\Test\Infrastructure\Entity\Question\QuestionPersonality;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:list-questions',
    description: 'List all personality questions from the database',
)]
class ListQuestionPersonalityCommand extends Command
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $repo = $this->entityManager->getRepository(QuestionPersonality::class);
        $questions = $repo->findAll();

        if (empty($questions)) {
            $io->warning("No questions found.");
            return Command::SUCCESS;
        }

        $io->title("List of Personality Questions:");
        foreach ($questions as $q) {
            $io->writeln("- " . $q->getText());
        }

        $io->success("Total: " . count($questions) . " question(s) found.");
        return Command::SUCCESS;
    }
}
