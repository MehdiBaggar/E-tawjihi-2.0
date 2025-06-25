<?php

namespace App\Command;

use App\Test\Domain\ValueObject\Enum\RIASECType;
use App\Test\Infrastructure\Entity\Question\QuestionPersonality;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:import-questions',
    description: 'Import personality questions from a CSV file',
)]
class ImportQuestionPersonalityCommand extends Command
{
    private EntityManagerInterface $entityManager;

    private const RIASEC_MAP = [
        'R' => RIASECType::REALISTIC,
        'I' => RIASECType::INVESTIGATIVE,
        'A' => RIASECType::ARTISTIC,
        'S' => RIASECType::SOCIAL,
        'E' => RIASECType::ENTERPRISING,
        'C' => RIASECType::CONVENTIONAL,
    ];

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $csvFile = __DIR__ . '/../../public_html/question.csv';

        if (!file_exists($csvFile)) {
            $io->error("CSV file not found at: $csvFile");
            return Command::FAILURE;
        }

        $nonPersonnaliteTexts = $this->getNonPersonnaliteTexts($csvFile);
        if (!empty($nonPersonnaliteTexts)) {
            $deleted = $this->deleteInvalidQuestions($nonPersonnaliteTexts);
            $io->writeln("Deleted $deleted wrong questions (same text as non-Personnalite rows).");
        }

        $imported = $this->importValidQuestions($csvFile, $io);
        $this->entityManager->flush();

        $io->success("Imported/Updated $imported Personnalite questions.");
        return Command::SUCCESS;
    }

    private function getNonPersonnaliteTexts(string $csvFile): array
    {
        $nonPersonnaliteTexts = [];
        if (($handle = fopen($csvFile, 'r')) !== false) {
            $header = fgetcsv($handle);
            $columns = array_flip($header);
            while (($row = fgetcsv($handle)) !== false) {
                if (($row[$columns['famille']] ?? null) !== 'Personnalite') {
                    $text = $row[$columns['question']] ?? null;
                    if ($text) {
                        $nonPersonnaliteTexts[] = $text;
                    }
                }
            }
            fclose($handle);
        }
        return $nonPersonnaliteTexts;
    }

    private function deleteInvalidQuestions(array $texts): int
    {
        return $this->entityManager->createQueryBuilder()
            ->delete(QuestionPersonality::class, 'q')
            ->where('q.text IN (:texts)')
            ->setParameter('texts', $texts)
            ->getQuery()
            ->execute();
    }

    private function importValidQuestions(string $csvFile, SymfonyStyle $io): int
    {
        $imported = 0;
        if (($handle = fopen($csvFile, 'r')) === false) {
            $io->error("Unable to reopen CSV file.");
            return 0;
        }

        $header = fgetcsv($handle);
        $columns = array_flip($header);

        while (($row = fgetcsv($handle)) !== false) {
            if (!$this->isValidPersonnaliteRow($row, $columns)) {
                continue;
            }

            $text = $row[$columns['question']] ?? null;
            $traitScoresJson = $row[$columns['traitScores']] ?? null;

            if (!$this->validateQuestionAndScores($text, $traitScoresJson, $io)) {
                continue;
            }

            $traitScores = json_decode($traitScoresJson, true);
            $this->normalizeTraitScores($traitScores);

            $this->createOrUpdateQuestion($text, $traitScores);

            $imported++;
        }

        fclose($handle);
        return $imported;
    }

    private function isValidPersonnaliteRow(array $row, array $columns): bool
    {
        return isset($row[$columns['famille']]) && $row[$columns['famille']] === 'Personnalite';
    }

    private function validateQuestionAndScores(?string $text, ?string $traitScoresJson, SymfonyStyle $io): bool
    {
        if (!$text || !$traitScoresJson) {
            $io->warning("Row with missing question or traitScores skipped.");
            return false;
        }

        $traitScores = json_decode($traitScoresJson, true);
        if (!is_array($traitScores)) {
            $io->warning("Invalid traitScores JSON for question: '$text'. Skipping.");
            return false;
        }

        return true;
    }

    private function normalizeTraitScores(array &$traitScores): void
    {
        foreach (array_keys(self::RIASEC_MAP) as $trait) {
            if (!array_key_exists($trait, $traitScores)) {
                $traitScores[$trait] = 0;
            }
        }
    }

    private function createOrUpdateQuestion(string $text, array $traitScores): void
    {
        $existing = $this->entityManager
            ->getRepository(QuestionPersonality::class)
            ->findOneBy(['text' => $text]);

        if ($existing) {
            $existing->setTraitScores($traitScores);
        } else {
            $question = (new QuestionPersonality())
                ->setText($text)
                ->setTraitScores($traitScores);
            $this->entityManager->persist($question);
        }
    }



}
