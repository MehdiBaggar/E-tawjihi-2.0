<?php

namespace App\User\Infrastructure\Mapper;

use App\User\Domain\ValueObject\AcademicInfo as DomainAcademicInfo;
use App\User\Infrastructure\Entity\AcademicInfo as DoctrineAcademicInfo;
use App\User\Infrastructure\Entity\User as DoctrineUser;

class AcademicInfoMapper
{
    public static function toDomain(?DoctrineAcademicInfo $doctrineInfo): ?DomainAcademicInfo
    {
        if (!$doctrineInfo) {
            return null;
        }

        return new DomainAcademicInfo(
            $doctrineInfo->getTypeEtablissement(),
            $doctrineInfo->getNiveauEtudes(),
            $doctrineInfo->getFiliere(),
            $doctrineInfo->getMoyenneGenerale(),
            $doctrineInfo->getAnneeObtentionBac(),
            $doctrineInfo->getLycee(),
            $doctrineInfo->getNomTuteur(),
            $doctrineInfo->getTelTuteur()
        );
    }

    public static function toDoctrine(?DomainAcademicInfo $domainInfo, DoctrineUser $user): ?DoctrineAcademicInfo
    {
        if (!$domainInfo) {
            return null;
        }

        $doctrineInfo = new DoctrineAcademicInfo();
        $doctrineInfo->setUser($user);
        $doctrineInfo->setTypeEtablissement($domainInfo->getInstitutionType());
        $doctrineInfo->setNiveauEtudes($domainInfo->getCurrentLevel());
        $doctrineInfo->setFiliere($domainInfo->getField());
        $doctrineInfo->setMoyenneGenerale($domainInfo->getAverage());
        $doctrineInfo->setAnneeObtentionBac($domainInfo->getBaccalaureateYear());
        $doctrineInfo->setLycee($domainInfo->getLycee());
        $doctrineInfo->setNomTuteur($domainInfo->getNomTuteur());
        $doctrineInfo->setTelTuteur($domainInfo->getTelTuteur());

        return $doctrineInfo;
    }

    public static function updateDoctrineFromDomain(
        ?DomainAcademicInfo $domainInfo,
        ?DoctrineAcademicInfo $existingInfo,
        DoctrineUser $user
    ): ?DoctrineAcademicInfo {
        if (!$domainInfo) {
            return null;
        }

        if (!$existingInfo) {
            $existingInfo = new DoctrineAcademicInfo();
            $existingInfo->setUser($user);
        }

        $existingInfo->setTypeEtablissement($domainInfo->getInstitutionType());
        $existingInfo->setNiveauEtudes($domainInfo->getCurrentLevel());
        $existingInfo->setFiliere($domainInfo->getField());
        $existingInfo->setMoyenneGenerale($domainInfo->getAverage());
        $existingInfo->setAnneeObtentionBac($domainInfo->getBaccalaureateYear());
        $existingInfo->setLycee($domainInfo->getLycee());
        $existingInfo->setNomTuteur($domainInfo->getNomTuteur());
        $existingInfo->setTelTuteur($domainInfo->getTelTuteur());

        return $existingInfo;
    }
}
