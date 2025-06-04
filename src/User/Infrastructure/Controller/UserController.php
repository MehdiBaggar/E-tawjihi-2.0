<?php

namespace App\User\Infrastructure\Controller;

use App\User\Application\DTO\AcademicInfoDto;
use App\User\Application\DTO\ChangePasswordDTO;
use App\User\Application\DTO\UpdateUserDTO;
use App\User\Application\Service\ChangePasswordService;
use App\User\Application\Service\UpdateAcademicInfoService;
use App\User\Application\Service\UpdateUserService;
use App\User\Domain\Repository\UserRepositoryInterface;
use App\User\Infrastructure\Entity\User;
use App\User\Infrastructure\Mapper\UserMapper;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;



class UserController extends AbstractController
{
    public function __construct(
        private ChangePasswordService $changePasswordService,
        private UpdateUserService $updateUserService,
        private UpdateAcademicInfoService $updateAcademicInfoService,
        private Security $security,

    ) {}

    #[Route('/', name: 'user_home')]
    public function index(): Response
    {
        return new Response('Hello from DDD User Controller!');
    }
    #[Route('/{id}/update', name: 'user_update', methods: ['GET', 'POST'])]
    public function update(
        int $id,
        Request $request,
        UpdateUserService $updateUserService,
        UserRepositoryInterface $userRepository
    ): Response {
        $user = $userRepository->findById($id);

        if (!$user) {
            throw $this->createNotFoundException('Utilisateur non trouvé');
        }
        $personalInfo = $user->getPersonalInfo();

        // On initialise le DTO avec les données actuelles de l'utilisateur
        $dto = new UpdateUserDTO(
            id: $id,
            email: $user->getEmail(),
            password: null, // On ne préremplit jamais le mot de passe
            firstName: $personalInfo?->getFirstName(),
            lastName: $personalInfo?->getLastName(),
            phoneNumber: $user->getPhoneNumber(),
            dateOfBirth: $personalInfo?->getDateOfBirth(),
            sex: $personalInfo?->getSex()
        );

        $form = $this->createFormBuilder($dto)
            ->add('email', EmailType::class, ['required' => false])
            ->add('password', PasswordType::class, ['required' => false])
            ->add('firstName', TextType::class, ['required' => false])
            ->add('lastName', TextType::class, ['required' => false])
            ->add('phoneNumber', TextType::class, ['required' => false])
            ->add('dateOfBirth', DateType::class, [
                'required' => false,
                'widget' => 'single_text',
            ])
            ->add('sex', ChoiceType::class, [
                'required' => false,
                'choices' => [
                    'Homme' => 'male',
                    'Femme' => 'female',
                    'Autre' => 'other',
                ],
                'placeholder' => 'Choisissez...',
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $updateUserService->update($dto);
                $this->addFlash('success', 'Utilisateur mis à jour avec succès.');
                return $this->redirectToRoute('user_update', ['id' => $id]);
            } catch (\Exception $e) {
                $this->addFlash('error', $e->getMessage());
            }
        }

        return $this->render('user/update.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/profile', name: 'app_profile')]
    #[IsGranted('ROLE_USER')]
    public function profile(): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        return $this->render('user/profile.html.twig', [
            'user' => $user,
        ]);
    }
    #[Route('/change-password', name: 'user_change_password')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function changePassword(Request $request): Response
    {
        $form = $this->createFormBuilder()
            ->add('oldPassword', PasswordType::class, [
                'label' => 'Ancien mot de passe',
                'required' => true,
            ])
            ->add('newPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les mots de passe ne correspondent pas.',
                'required' => true,
                'first_options'  => ['label' => 'Nouveau mot de passe'],
                'second_options' => ['label' => 'Confirmer le mot de passe'],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Changer le mot de passe',
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            try {
                // Récupération de l'utilisateur connecté
                /** @var \App\User\Infrastructure\Entity\User $infrastructureUser */
                $infrastructureUser = $this->security->getUser();
                if (!$infrastructureUser instanceof \App\User\Infrastructure\Entity\User) {
                    throw new \LogicException('Utilisateur non valide.');
                }

                $domainUser = UserMapper::toDomain($infrastructureUser);


                // On crée le DTO
                $dto = new ChangePasswordDTO(
                    oldPassword: $data['oldPassword'],
                    newPassword: $data['newPassword']
                );

                // On appelle le service
                $this->changePasswordService->changePassword($domainUser, $dto);

                $this->addFlash('success', 'Mot de passe modifié avec succès.');

                return $this->redirectToRoute('home'); // ou une autre route
            } catch (\Exception $e) {
                $this->addFlash('error', $e->getMessage());
                return $this->redirectToRoute('user_change_password');
            }
        }

        return $this->render('user/change_password.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/academic-info', name: 'user_update_academic_info', methods: ['GET', 'POST'])]
    public function updateAcademicInfo(
        int $id,
        Request $request,
        UpdateAcademicInfoService $updateAcademicInfoService,
        UserRepositoryInterface $userRepository
    ): Response {
        $user = $userRepository->findById($id);

        if (!$user) {
            throw $this->createNotFoundException('Utilisateur non trouvé');
        }

        $academicInfo = $user->getAcademicInfo();

        $dto = new AcademicInfoDto(
            institutionType: $academicInfo?->getInstitutionType(),
            currentLevel: $academicInfo?->getCurrentLevel(),
            field: $academicInfo?->getField(),
            average: $academicInfo?->getAverage(),
            baccalaureateYear: $academicInfo?->getBaccalaureateYear(),
            lycee: $academicInfo?->getLycee(),nomTuteur: $academicInfo?->getNomTuteur(),telTuteur: $academicInfo?->getTelTuteur()
        );

        $form = $this->createFormBuilder($dto)
            ->add('institutionType', TextType::class, ['required' => false])
            ->add('currentLevel', TextType::class, ['required' => false])
            ->add('field', TextType::class, ['required' => false])
            ->add('average', TextType::class, ['required' => false])
            ->add('baccalaureateYear', TextType::class, ['required' => false])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $updateAcademicInfoService->update($id, $dto);
                $this->addFlash('success', 'Informations académiques mises à jour avec succès.');
                return $this->redirectToRoute('user_update_academic_info', ['id' => $id]);
            } catch (\Exception $e) {
                $this->addFlash('error', $e->getMessage());
            }
        }

        return $this->render('user/update_academic_info.html.twig', [
            'form' => $form->createView()
        ]);
    }












}
