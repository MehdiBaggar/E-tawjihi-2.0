<?php

namespace App\User\Infrastructure\Controller;

use App\User\Application\DTO\AcademicInfoDto;
use App\User\Application\DTO\ChangePasswordDTO;
use App\User\Application\DTO\UpdateUserDTO;
use App\User\Application\Service\ArchiveUserService;
use App\User\Application\Service\ChangePasswordService;
use App\User\Application\Service\UpdateAcademicInfoService;
use App\User\Application\Service\UpdateUserService;
use App\Test\Infrastructure\Repository\TestPersonalityRepository;

use App\User\Infrastructure\Entity\User;
use App\User\Infrastructure\Mapper\UserMapper;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Psr\Log\LoggerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class UserApiController extends AbstractController
{
    public function __construct(
        private ChangePasswordService $changePasswordService,
        private UpdateUserService $updateUserService, // Add this
        private UpdateAcademicInfoService $updateAcademicInfoService,
        private Security $security,
        private LoggerInterface $logger,
        private ValidatorInterface $validator,
        private TestPersonalityRepository $testPersonalityService,

    ) {}


    #[Route('/{id}/archive', name: 'user_archive', methods: ['GET'])]
    public function archive(int $id, ArchiveUserService $archiveUserService): JsonResponse
    {
        try {
            $archiveUserService->archive($id);

            return new JsonResponse(['message' => 'Utilisateur archivé avec succès'], 200);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 404);
        }
    }

    #[Route('/api/me', name: 'api_me', methods: ['GET'])]
    public function me(Security $security): JsonResponse
    {
        $this->logger->info('[API /api/me] Request received.');

        /** @var User|null $user */
        $user = $security->getUser();

        if (!$user) {
            $this->logger->warning('[API /api/me] User not authenticated.');
            return $this->json(['error' => 'Unauthenticated'], 401);
        }

        $this->logger->info('[API /api/me] User authenticated.', [
            'userId' => $user->getId(),
            'email' => $user->getEmail()
        ]);

        $personalInfo = $this->extractPersonalInfo($user);
        $academicInfo = $this->extractAcademicInfo($user);

        $hasCompletedPersonalityTest = $this->testPersonalityService->hasUserCompletedPersonalityTest($user);

        $responseData = $this->buildUserResponse($user, $personalInfo, $academicInfo);
        $responseData['hasCompletedPersonalityTest'] = $hasCompletedPersonalityTest;

        $responseData['createdAt'] = $user->getCreatedAt()?->format('Y-m-d H:i:s');


        $this->logger->info('[API /api/me] Responding with user data.', [
            'data_being_sent' => $responseData
        ]);


        return $this->json($responseData);
    }


    #[Route('/api/personal-info/update', name: 'api_user_personal_info_update', methods: ['POST'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function updatePersonalInfoApi(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $this->security->getUser();
        if (!$user) {
            $this->logger->warning('[API Personal Info Update] User not authenticated.');
            return new JsonResponse(['error' => 'Utilisateur non authentifié.'], Response::HTTP_UNAUTHORIZED);
        }

        $data = json_decode($request->getContent(), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->logger->error('[API Personal Info Update] Invalid JSON payload.', ['json_error' => json_last_error_msg()]);
            return new JsonResponse(['error' => 'Invalid JSON payload: ' . json_last_error_msg()], Response::HTTP_BAD_REQUEST);
        }

        $this->logger->info('[API Personal Info Update] Request received.', ['userId' => $user->getId(), 'data' => $data]);

        $dto = new UpdateUserDTO(
            id: $user->getId(), // Crucial for the service
            email: $data['email'] ?? $user->getEmail(), // Use current if not provided
            password: null, // Password is not updated here
            firstName: $data['prenom'] ?? null,
            lastName: $data['nom'] ?? null,
            phoneNumber: $data['tel'] ?? null,
            dateOfBirth: isset($data['age']) && !empty($data['age']) ? new \DateTime($data['age']) : null,
            sex: $data['genre'] ?? null
        );

        $errors = $this->validator->validate($dto);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[$error->getPropertyPath()][] = $error->getMessage();
            }
            $this->logger->warning('[API Personal Info Update] Validation errors.', ['errors' => $errorMessages]);
            return new JsonResponse(['error' => 'Validation failed', 'details' => $errorMessages], Response::HTTP_BAD_REQUEST);
        }

        try {
            $this->updateUserService->update($dto);
            $this->logger->info('[API Personal Info Update] Personal info updated successfully.', ['userId' => $user->getId()]);
            // Vue expects a success notification, so a simple message is good.
            // The Vuex store will be re-fetched by the frontend.
            return new JsonResponse(['message' => 'Informations personnelles modifiées avec succès !'], Response::HTTP_OK);
        } catch (\Exception $e) {
            $this->logger->error('[API Personal Info Update] Error updating personal info.', [
                'userId' => $user->getId(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    #[Route('/api/academic-info/update', name: 'api_user_academic_info_update', methods: ['POST'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function updateAcademicInfoApi(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $this->security->getUser();
        if (!$user) {
            $this->logger->warning('[API Academic Info Update] User not authenticated.');
            return new JsonResponse(['error' => 'Utilisateur non authentifié.'], Response::HTTP_UNAUTHORIZED);
        }

        $data = json_decode($request->getContent(), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->logger->error('[API Academic Info Update] Invalid JSON payload.', ['json_error' => json_last_error_msg()]);
            return new JsonResponse(['error' => 'Invalid JSON payload: ' . json_last_error_msg()], Response::HTTP_BAD_REQUEST);
        }

        $this->logger->info('[API Academic Info Update] Request received.', ['userId' => $user->getId(), 'data' => $data]);

        // Map Vue `datas` to AcademicInfoDto
        // Vue `datas.niveau` -> `currentLevel` (or whatever your DTO property is, e.g., 'niveauDetude')
        // Vue `datas.filiere` -> `field` (or 'filiere')
        // Vue `datas.typeEcole` -> `institutionType` (or 'typeEtablissement')
        // Vue `datas.anneeObtentionBac` -> `baccalaureateYear` (or 'anneeObtentionBac')

        // Your AcademicInfoDto has: institutionType, currentLevel, field, average, baccalaureateYear
        // Your /api/me returns for academicInfo: niveauDetude, filiere, typeEtablissement, anneeObtentionBac
        // Let's assume the DTO properties align with the /api/me naming for consistency or that you'll adjust DTO.
        // For this example, I'll use the names from your DTO definition if different.
        $dto = new AcademicInfoDto(
            institutionType: $data['typeEcole'] ?? null,    // Maps to 'typeEtablissement' from /api/me
            currentLevel: $data['niveau'] ?? null,       // Maps to 'niveauDetude' from /api/me
            field: $data['filiere'] ?? null,          // Maps to 'filiere' from /api/me
            average: $data['average'] ?? null,
            baccalaureateYear: isset($data['anneeObtentionBac']) ? (int)$data['anneeObtentionBac'] : null,
            lycee: $data['lycee'] ?? null,
            nomTuteur: $data['nomTuteur'] ?? null,
            telTuteur: $data['telTuteur'] ?? null,

        );

        // Fields from Vue `datas` NOT in AcademicInfoDto:
        // - langueEtude
        // - lycee
        // - nomTuteur
        // - telTuteur
        // You'll need to decide how to handle these:
        // 1. Add them to AcademicInfoDto and its service.
        // 2. Create a separate DTO/Service for them.
        // 3. Store them directly on the User entity if appropriate and not part of "Academic Info" domain.
        // For now, this will only update fields covered by AcademicInfoDto.

        $errors = $this->validator->validate($dto);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[$error->getPropertyPath()][] = $error->getMessage();
            }
            $this->logger->warning('[API Academic Info Update] Validation errors.', ['errors' => $errorMessages]);
            return new JsonResponse(['error' => 'Validation failed', 'details' => $errorMessages], Response::HTTP_BAD_REQUEST);
        }

        try {
            $this->updateAcademicInfoService->update($user->getId(), $dto);
            $this->logger->info('[API Academic Info Update] Academic info updated successfully.', ['userId' => $user->getId()]);
            return new JsonResponse(['message' => 'Informations académiques modifiées avec succès !'], Response::HTTP_OK);
        } catch (\Exception $e) {
            $this->logger->error('[API Academic Info Update] Error updating academic info.', [
                'userId' => $user->getId(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


// ...

    #[Route('/api/change-password', name: 'api_user_change_password', methods: ['POST'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function changePasswordApi(Request $request, LoggerInterface $logger): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $logger->info('Requête de changement de mot de passe reçue.', [
            'payload' => $data,
            'user_ip' => $request->getClientIp(),
            'user_agent' => $request->headers->get('User-Agent'),
        ]);

        if (!isset($data['oldPassword'], $data['newPassword'])) {
            $logger->warning('Champs manquants dans la requête.', ['payload' => $data]);
            return new JsonResponse(['error' => 'Champs manquants.'], 400);
        }

        try {
            $infrastructureUser = $this->security->getUser();

            if (!$infrastructureUser instanceof \App\User\Infrastructure\Entity\User) {
                $logger->error('Utilisateur non valide ou non trouvé.', [
                    'user_class' => get_class($infrastructureUser),
                ]);
                throw new \LogicException('Utilisateur non valide.');
            }

            $logger->info('Utilisateur identifié.', ['user_id' => $infrastructureUser->getId()]);

            $domainUser = UserMapper::toDomain($infrastructureUser);

            $dto = new ChangePasswordDTO(
                oldPassword: $data['oldPassword'],
                newPassword: $data['newPassword']
            );

            $this->changePasswordService->changePassword($domainUser, $dto);

            $logger->info('Mot de passe changé avec succès.', ['user_id' => $infrastructureUser->getId()]);

            return new JsonResponse(['message' => 'Mot de passe modifié avec succès.']);
        } catch (\Exception $e) {
            $logger->error('Erreur lors du changement de mot de passe.', [
                'exception' => $e,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return new JsonResponse(['error' => $e->getMessage()], 400);
        }
    }
    private function extractPersonalInfo(User $user): array
    {
        $info = $user->getPersonalInfo();

        if (!$info) {
            $this->logger->warning('[API /api/me] PersonalInfo NOT found for user.', [
                'userId' => $user->getId()
            ]);
            return ['firstName' => null, 'lastName' => null, 'Sex' => null, 'dateOfBirth' => null];
        }

        $this->logger->info('[API /api/me] PersonalInfo found.', [
            'userId' => $user->getId(),
            'pi_firstName' => $info->getFirstName(),
            'pi_lastName' => $info->getLastName(),
            'pi_sex' => $info->getSex(),
            'pi_dateOfBirth' => $info->getDateOfBirth(),
            'personalInfoClass' => get_class($info)
        ]);

        if (!$info->getFirstName() || !$info->getLastName() || !$info->getSex() || !$info->getDateOfBirth()) {
            $this->logger->warning('[API /api/me] Some PersonalInfo fields are null.', [
                'firstName' => $info->getFirstName(),
                'lastName' => $info->getLastName()
            ]);
        }

        return [
            'firstName' => $info->getFirstName(),
            'lastName' => $info->getLastName(),
            'Sex' => $info->getSex(),
            'dateOfBirth' => $info->getDateOfBirth()
        ];
    }

    private function extractAcademicInfo(User $user): ?array
    {
        $info = $user->getAcademicInfo();
        if (!$info) {
            return null;
        }

        return [
            'niveauDetude' => $info->getNiveauEtudes(),
            'filiere' => $info->getFiliere(),
            'typeEtablissement' => $info->getTypeEtablissement(),
            'anneeObtentionBac' => $info->getAnneeObtentionBac(),
            'lycee' => $info->getLycee(),
            'nomTuteur' => $info->getNomTuteur(),
            'telTuteur' => $info->getTelTuteur()
        ];
    }

    private function buildUserResponse(User $user, array $personalInfo, ?array $academicInfo): array
    {
        return [
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'phoneNumber' => $user->getPhoneNumber(),
            'roles' => $user->getRoles(),
            'personalInfo' => $personalInfo,
            'academicInfo' => $academicInfo
        ];
    }








}
