<?php

namespace App\Core\UI\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/{vueRouting}', name: 'vue_app', requirements: ['vueRouting' => '^(?!api|user|test|_(profiler|wdt)).*'], defaults: ['vueRouting' => null])]
    public function index(): Response
    {
        return $this->render('base.html.twig');
    }
}
