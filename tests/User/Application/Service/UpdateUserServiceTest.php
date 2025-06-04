<?php

namespace App\Tests\User\Application\Service;

use PHPUnit\Framework\TestCase;
use App\User\Application\Service\UpdateUserService;
use App\User\Application\DTO\UpdateUserDTO;
use App\User\Domain\Repository\UserRepositoryInterface;
use App\User\Domain\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UpdateUserServiceTest extends TestCase
{
    public function testUpdateUserWithNewData(): void
    {
        // Arrange
        $user = User::create('old@example.com', 'old_password');
        $user->setId(1); // simulate existing user

        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $userRepository->method('findById')->with(1)->willReturn($user);
        $userRepository->expects($this->once())->method('save')->with($user);

        $passwordHasher = $this->createMock(UserPasswordHasherInterface::class);
        $passwordHasher->method('hashPassword')->willReturn('new_hashed_password');

        $dto = new UpdateUserDTO(
            id: 1,
            email: 'new@example.com',
            password: 'new_password',
            firstName: 'Mehdi',
            lastName: 'Baggar',
            phoneNumber: '0612345678',
            dateOfBirth: new \DateTime('2000-01-01'),
            sex: 'Homme'
        );

        $service = new UpdateUserService($userRepository, $passwordHasher);

        // Act
        $service->update($dto);

        // Assert
        $this->assertSame('new@example.com', $user->getEmail());
        $this->assertSame('new_hashed_password', $user->getPassword());
        $this->assertSame('Mehdi', $user->getFirstName());
        $this->assertSame('Baggar', $user->getLastName());
        $this->assertSame('0612345678', $user->getPhoneNumber());
        $this->assertEquals(new \DateTime('2000-01-01'), $user->getDateOfBirth());
        $this->assertSame('Homme', $user->getSex());
    }

    public function testUpdateThrowsExceptionIfUserNotFound(): void
    {
        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $userRepository->method('findById')->with(999)->willReturn(null);

        $passwordHasher = $this->createMock(UserPasswordHasherInterface::class);

        $dto = new UpdateUserDTO(id: 999);

        $service = new UpdateUserService($userRepository, $passwordHasher);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('User not found');

        $service->update($dto);
    }
}
