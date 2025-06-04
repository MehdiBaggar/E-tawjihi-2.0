<?php
namespace App\User\Infrastructure\Mapper;

use App\User\Domain\Entity\User as DomainUser;
use App\User\Infrastructure\Entity\User as DoctrineUser;

final class UserMapper
{
    public static function toDomain(DoctrineUser $doctrineUser): DomainUser
    {
        $domainUser = DomainUser::create(
            $doctrineUser->getEmail(),
            $doctrineUser->getPassword(),
            $doctrineUser->getRoles()
        );

        $domainUser->setId($doctrineUser->getId());
        $domainUser->setPhoneNumber($doctrineUser->getPhoneNumber());


        if ($doctrineUser->getPersonalInfo()) {
            $domainUser->setPersonalInfo(
                PersonalInfoMapper::toDomain($doctrineUser->getPersonalInfo())
            );
        }

        if ($doctrineUser->getAcademicInfo()) {
            $domainUser->setAcademicInfo(
                AcademicInfoMapper::toDomain($doctrineUser->getAcademicInfo())
            );
        }

        return $domainUser;
    }

    public static function toDoctrine(DomainUser $domainUser): DoctrineUser
    {
        $doctrineUser = new DoctrineUser($domainUser->getEmail());
        $doctrineUser->setPassword($domainUser->getPassword());
        $doctrineUser->setRoles($domainUser->getRoles());
        $doctrineUser->setPhoneNumber($domainUser->getPhoneNumber());

        $personalInfo = PersonalInfoMapper::toDoctrine($domainUser->getPersonalInfo(), $doctrineUser);
        $doctrineUser->setPersonalInfo($personalInfo);

        $academicInfo = AcademicInfoMapper::toDoctrine($domainUser->getAcademicInfo(), $doctrineUser);
        $doctrineUser->setAcademicInfo($academicInfo);


        return $doctrineUser;
    }
    public static function updateDoctrineFromDomain(DomainUser $domain, DoctrineUser $doctrine): void
    {
        $doctrine->setEmail($domain->getEmail());
        $doctrine->setPhoneNumber($domain->getPhoneNumber());
        if ($domain->isArchived()){
            $doctrine->archive();
        }

        // Et mettre à jour PersonalInfo
        if ($domain->getPersonalInfo()) {
            $personalInfo = PersonalInfoMapper::updateDoctrineFromDomain(
                $domain->getPersonalInfo(),
                $doctrine->getPersonalInfo(),
                $doctrine
            );
            $doctrine->setPersonalInfo($personalInfo);
        }

        if ($domain->getAcademicInfo()) {
            $academicInfo = AcademicInfoMapper::updateDoctrineFromDomain(
                $domain->getAcademicInfo(),
                $doctrine->getAcademicInfo(),
                $doctrine
            );
            $doctrine->setAcademicInfo($academicInfo);
        }
    }
}
