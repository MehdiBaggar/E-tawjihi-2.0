<?php

namespace App\Tests\User\Application\Service;

use PHPUnit\Framework\TestCase;
use App\User\Application\Service\ArchiveUserService;
use App\User\Domain\Entity\User;
use App\User\Domain\Repository\UserRepositoryInterface;

class ArchiveUserServiceTest extends TestCase
{
    public function testArchiveUserSuccessfully(): void
    {
        // Arrange
        $user = $this->createMock(User::class);
        $user->expects($this->once())->method('archive');

        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $userRepository->method('findById')->with(1)->willReturn($user);
        $userRepository->expects($this->once())->method('save')->with($user);

        $service = new ArchiveUserService($userRepository);

        // Act
        $service->archive(1);

        // Assert
        $this->assertTrue(true); // no exception = success
    }

    public function testArchiveThrowsExceptionIfUserNotFound(): void
    {
        // Arrange
        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $userRepository->method('findById')->with(99)->willReturn(null);

        $service = new ArchiveUserService($userRepository);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('User not found');

        // Act
        $service->archive(99);
    }
}
