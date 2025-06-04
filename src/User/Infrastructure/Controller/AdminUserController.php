<?php

namespace App\User\Infrastructure\Controller;

use App\User\Application\Service\ArchiveUserService;
use App\User\Domain\Repository\UserRepositoryInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminUserController extends AbstractController
{
    #[Route('/admin/', name: 'admin_user_list')]
    #[IsGranted('ROLE_ADMIN')]
    public function list(UserRepositoryInterface $userRepository): Response
    {
        $users = $userRepository->findAllActive();

        return $this->render('admin/user_list.html.twig', [
            'users' => $users,
        ]);
    }
    #[Route('/admin/archived', name: 'admin_users_archived')]
    #[IsGranted('ROLE_ADMIN')]
    public function listArchived(UserRepositoryInterface $userRepository): Response
    {
        $users = $userRepository->findAllArchived();

        return $this->render('admin/archivedUser_list.html.twig', [
            'users' => $users,
        ]);
    }
    #[Route('/admin/users/{id}/archive', name: 'admin_user_archive')]
    #[IsGranted('ROLE_ADMIN')]
    public function archive(int $id, ArchiveUserService $archiveUserService): Response
    {
        try {
            $archiveUserService->archive($id);

            return $this->redirectToRoute('admin_user_list');
        } catch (\Exception $e) {
            return $this->redirectToRoute('admin_user_list');
        }
    }
    #[Route('/admin/users/{id}/unarchive', name: 'admin_user_unarchive')]
    #[IsGranted('ROLE_ADMIN')]
    public function unarchive(int $id, ArchiveUserService $archiveUserService): Response
    {
        try {
            $archiveUserService->unarchive($id);

            return $this->redirectToRoute('admin_user_list');
        } catch (\Exception $e) {
            return $this->redirectToRoute('admin_user_list');
        }
    }

}
