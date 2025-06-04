<?php

namespace App\Tests\User\Domain\Entity;

use PHPUnit\Framework\TestCase;
use App\User\Domain\Entity\User;

class UserTest extends TestCase
{
    public function testCreateUser(): void
    {
        $user = User::create(
            email: 'test@example.com',
            password: 'hashed-password',
            roles: ['ROLE_USER']
        );

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('test@example.com', $user->getEmail());
        $this->assertEquals('hashed-password', $user->getPassword());
        $this->assertEquals(['ROLE_USER'], $user->getRoles());
        $this->assertFalse($user->isArchived());
        $this->assertNotNull($user->getCreatedAt());
    }

    public function testArchiveUser(): void
    {
        $user = User::create(
            email: 'test@example.com',
            password: 'password'
        );

        $this->assertFalse($user->isArchived());

        $user->archive();

        $this->assertTrue($user->isArchived());
    }

    public function testSetters(): void
    {
        $user = User::create('a@b.com', 'pwd');
        $user->setEmail('new@example.com');
        $user->setPassword('new-password');
        $user->setRoles(['ROLE_ADMIN']);
        $user->setFirstName('Ali');
        $user->setLastName('Ben Ali');

        $this->assertEquals('new@example.com', $user->getEmail());
        $this->assertEquals('new-password', $user->getPassword());
        $this->assertEquals(['ROLE_ADMIN'], $user->getRoles());
        $this->assertEquals('Ali', $user->getFirstName());
        $this->assertEquals('Ben Ali', $user->getLastName());
    }
}
