<?php

namespace App\User\Application\Service;

use App\User\Application\Dto\AcademicInfoDto;
use App\User\Domain\Repository\UserRepositoryInterface;
use App\User\Domain\ValueObject\AcademicInfo;

class UpdateAcademicInfoService
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}

    public function update(int $userId, AcademicInfoDto $dto): void
    {
        $user = $this->userRepository->findById($userId);

        if (!$user) {
            throw new \Exception('User not found');
        }

        // Si au moins une donnée est fournie, on met à jour
        if (
            $dto->institutionType !== null ||
            $dto->currentLevel !== null ||
            $dto->field !== null ||
            $dto->average !== null ||
            $dto->baccalaureateYear !== null ||
            $dto->lycee !== null ||
            $dto->nomTuteur !== null ||
            $dto->telTuteur !== null
        ) {
            $academicInfo = new AcademicInfo(
                $dto->institutionType,
                $dto->currentLevel,
                $dto->field,
                $dto->average,
                $dto->baccalaureateYear,
                $dto->lycee,
                $dto->nomTuteur,
                $dto->telTuteur

            );

            $user->setAcademicInfo($academicInfo);
        }

        $this->userRepository->save($user);
    }
}
