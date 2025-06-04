<?php
namespace App\User\Application\Service;

use App\User\Domain\Repository\UserRepositoryInterface;

class ArchiveUserService
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}

    public function archive(int $id): void
    {
        $user = $this->userRepository->findById($id);

        if (!$user) {
            throw new \Exception('User not found');
        }

        $user->archive();
        $this->userRepository->save($user);
    }

    public function unarchive(int $id): void
    {
        $user = $this->userRepository->findById($id);

        if (!$user) {
            throw new \Exception('User not found');
        }

        $user->unarchive();
        $this->userRepository->save($user);
    }
}
