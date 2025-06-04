<?php

namespace App\User\Infrastructure\Controller\auth;

use App\User\Application\DTO\RegisterUserDTO;
use App\User\Application\Service\ChangePasswordService; // Not used in register, but keeping as per original
use App\User\Application\Service\RegisterUserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType; // Import RepeatedType
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\DBAL\Exception;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class RegistrationController extends AbstractController
{
    public function __construct(
        private ChangePasswordService $changePasswordService,
    ) {}

    #[Route('/register', name: 'app_register')]
    public function register(Request $request, RegisterUserService $registerUserService): Response
    {
        $form = $this->createFormBuilder()
            ->add('email', EmailType::class, [
                'label' => 'Email',
            ])
            ->add('phoneNumber', TextType::class, [
                'label' => 'Numéro de téléphone',
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options'  => ['label' => 'Mot de passe'],
                'second_options' => ['label' => 'Confirmez le mot de passe'],
                'first_name' => 'pass',
                'second_name' => 'confirm',
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $dto = new RegisterUserDTO(
                $data['email'],
                $data['password'],
                $data['phoneNumber']
            );

            try {
                $registerUserService->register($dto);

                $this->addFlash('success', 'Registration successful! Please log in.');

                return $this->redirectToRoute('app_login');

            } catch (UniqueConstraintViolationException $e) {
                // Check which field caused the issue
                $message = $e->getMessage();

                if (str_contains($message, 'UNIQ_1483A5E9E7927C74')) {
                    $this->addFlash('error', 'Cet email existe déjà.');
                } elseif (str_contains($message, 'UNIQ_1483A5E9_XXXXXXXX')) {
                    $this->addFlash('error', 'Ce numéro de téléphone existe déjà.');
                } else {
                    $this->addFlash('error', 'Erreur lors de l\'inscription. Veuillez réessayer.');
                }
            } catch (\Exception $e) {
                $this->addFlash('error', 'Une erreur inconnue est survenue.');
            }
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

}