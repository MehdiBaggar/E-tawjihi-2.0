<?php
namespace App\User\Infrastructure\Mapper;

use App\User\Domain\ValueObject\PersonalInfo as DomainPersonalInfo;
use App\User\Infrastructure\Entity\PersonalInfo as DoctrinePersonalInfo;
use App\User\Infrastructure\Entity\User as DoctrineUser;

class PersonalInfoMapper
{
    public static function toDomain(?DoctrinePersonalInfo $doctrineInfo): ?DomainPersonalInfo
    {
        if (!$doctrineInfo) {
            return null;
        }

        return new DomainPersonalInfo(
            $doctrineInfo->getFirstName(),
            $doctrineInfo->getLastName(),
            $doctrineInfo->getDateOfBirth(),
            $doctrineInfo->getSex()
        );
    }

    public static function toDoctrine(?DomainPersonalInfo $domainInfo, DoctrineUser $user): ?DoctrinePersonalInfo
    {
        if (!$domainInfo) {
            return null;
        }

        $doctrineInfo = new DoctrinePersonalInfo();
        $doctrineInfo->setUser($user);
        $doctrineInfo->setFirstName($domainInfo->getFirstName());
        $doctrineInfo->setLastName($domainInfo->getLastName());
        $doctrineInfo->setDateOfBirth($domainInfo->getDateOfBirth());
        $doctrineInfo->setSex($domainInfo->getSex());

        return $doctrineInfo;
    }
    public static function updateDoctrineFromDomain(
        ?DomainPersonalInfo $domainInfo,
        ?DoctrinePersonalInfo $existingInfo,
        DoctrineUser $user
    ): ?DoctrinePersonalInfo {
        if (!$domainInfo) {
            return null;
        }

        if (!$existingInfo) {
            $existingInfo = new DoctrinePersonalInfo();
            $existingInfo->setUser($user);
        }

        $existingInfo->setFirstName($domainInfo->getFirstName());
        $existingInfo->setLastName($domainInfo->getLastName());
        $existingInfo->setDateOfBirth($domainInfo->getDateOfBirth());
        $existingInfo->setSex($domainInfo->getSex());

        return $existingInfo;
    }
}
