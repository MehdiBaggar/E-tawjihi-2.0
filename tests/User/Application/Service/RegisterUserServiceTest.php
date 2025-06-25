<?php

namespace App\Tests\User\Application\Service;

use PHPUnit\Framework\TestCase;
use App\User\Application\Service\RegisterUserService;
use App\User\Application\DTO\RegisterUserDTO;
use App\User\Domain\Entity\User as DomainUser;
use App\User\Domain\Repository\UserRepositoryInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\User\Infrastructure\Entity\User as InfrastructureUser;

class RegisterUserServiceTest extends TestCase
{
    public function testRegisterUserSuccessfully(): void
    {
        // Arrange : créer les mocks
        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $passwordHasher = $this->createMock(UserPasswordHasherInterface::class);

        $dto = new RegisterUserDTO(
            email: 'john@example.com',
            plainPassword: 'securepassword',
            phoneNumber: '0123456789'
        );

        $passwordHasher->expects($this->once())
            ->method('hashPassword')
            ->with(
                $this->isInstanceOf(InfrastructureUser::class),
                $dto->plainPassword
            )
            ->willReturn('hashed_securepassword');

        $userRepository->expects($this->once())
            ->method('save')
            ->with($this->callback(function (DomainUser $user) {
                return $user->getEmail() === 'john@example.com';
            }));

        // Act
        $service = new RegisterUserService($userRepository, $passwordHasher);
        $service->register($dto);

        // Assert : pas d'exception = succès
        $this->assertTrue(true);
    }
}
