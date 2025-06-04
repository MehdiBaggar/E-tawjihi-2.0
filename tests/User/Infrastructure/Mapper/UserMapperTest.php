<?php

namespace App\Tests\User\Infrastructure\Mapper;

use App\User\Domain\Entity\User as DomainUser;
use App\User\Infrastructure\Entity\User as DoctrineUser;
use App\User\Infrastructure\Mapper\UserMapper;
use PHPUnit\Framework\TestCase;

class UserMapperTest extends TestCase
{
    public function testToDomain(): void
    {
        // Création d'un utilisateur Doctrine (infrastructure)
        $doctrineUser = new DoctrineUser('test@example.com');
        $doctrineUser->setPassword('hashed_password');
        $doctrineUser->setRoles(['ROLE_USER']);
        $doctrineUser->setFirstName('John');
        $doctrineUser->setLastName('Doe');

        // Injection manuelle de l'ID via réflexion
        $reflection = new \ReflectionClass($doctrineUser);
        $idProperty = $reflection->getProperty('id');
        $idProperty->setAccessible(true);
        $idProperty->setValue($doctrineUser, 42);

        // Mapper vers domaine
        $domainUser = UserMapper::toDomain($doctrineUser);

        $this->assertInstanceOf(DomainUser::class, $domainUser);
        $this->assertEquals('test@example.com', $domainUser->getEmail());
        $this->assertEquals('hashed_password', $domainUser->getPassword());
        $this->assertEqualsCanonicalizing(['ROLE_USER'], $domainUser->getRoles());
        $this->assertEquals(42, $domainUser->getId());
        $this->assertEquals('John', $domainUser->getFirstName());
        $this->assertEquals('Doe', $domainUser->getLastName());
    }

    public function testToDoctrine(): void
    {
        // Création d'un utilisateur Domaine
        $domainUser = DomainUser::create('admin@example.com', 'secure_password', ['ROLE_ADMIN']);
        $domainUser->setFirstName('Alice');
        $domainUser->setLastName('Smith');

        // Injection manuelle de l'ID
        $domainUserReflection = new \ReflectionClass($domainUser);
        $idProperty = $domainUserReflection->getProperty('id');
        $idProperty->setAccessible(true);
        $idProperty->setValue($domainUser, 99);

        // Mapper vers Doctrine
        $doctrineUser = UserMapper::toDoctrine($domainUser);

        $this->assertInstanceOf(DoctrineUser::class, $doctrineUser);
        $this->assertEquals('admin@example.com', $doctrineUser->getEmail());
        $this->assertEquals('secure_password', $doctrineUser->getPassword());
        $this->assertEqualsCanonicalizing(['ROLE_ADMIN', 'ROLE_USER'], $doctrineUser->getRoles());
        $this->assertEquals('Alice', $doctrineUser->getFirstName());
        $this->assertEquals('Smith', $doctrineUser->getLastName());

        // Vérifie l'ID avec réflexion
        $doctrineUserReflection = new \ReflectionClass($doctrineUser);
        $idProp = $doctrineUserReflection->getProperty('id');
        $idProp->setAccessible(true);
        $this->assertEquals(99, $idProp->getValue($doctrineUser));
    }
}
