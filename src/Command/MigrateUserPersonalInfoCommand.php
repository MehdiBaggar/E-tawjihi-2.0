<?php

namespace App\Command;

use App\User\Infrastructure\Entity\User;
use App\User\Infrastructure\Entity\PersonalInfo;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
name: 'app:migrate-user-personal-info',
description: 'Migrate firstName, lastName, and sex from User to PersonalInfo',
)]
class MigrateUserPersonalInfoCommand extends Command
{
private EntityManagerInterface $em;

public function __construct(EntityManagerInterface $em)
{
parent::__construct();
$this->em = $em;
}

protected function execute(InputInterface $input, OutputInterface $output): int
{
$users = $this->em->getRepository(User::class)->findAll();

foreach ($users as $user) {
    // Vérifie si un PersonalInfo est déjà associé
    $existingPersonalInfo = $this->em->getRepository(PersonalInfo::class)
        ->findOneBy(['user' => $user]);

    if ($existingPersonalInfo) {
        continue; // Ne rien faire si déjà existant
    }
    $firstName = $user->getFirstName();
    $lastName = $user->getLastName();
    $sex = $user->getSex();
    $dob = $user->getDateOfBirth();

    if ($firstName || $lastName || $sex || $dob) {
        $personalInfo = new PersonalInfo();
        $personalInfo->setFirstName($firstName);
        $personalInfo->setLastName($lastName);
        $personalInfo->setSex($sex);
        $personalInfo->setDateOfBirth($dob);
        $personalInfo->setUser($user);

        $this->em->persist($personalInfo);
        }
    }

$this->em->flush();

$output->writeln('<info>✅ Données migrées avec succès vers PersonalInfo !</info>');

return Command::SUCCESS;
}
}
