<?php
namespace App\User\Infrastructure\Controller\auth;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class AuthController extends AbstractController
{
    #[Route('/api/auth/status', name: 'auth_status')]
    public function authStatus(): JsonResponse
    {
        $user = $this->getUser();

        return $this->json([
            'authenticated' => $user !== null,
            'user' => $user?->getUserIdentifier(),
        ]);
    }
}