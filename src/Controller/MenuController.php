<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MenuController extends AbstractController
{
    #[Route('/menu', name: 'app_menu')]
    public function index(): Response
    {
        // Récupère l'utilisateur connecté
        $user = $this->getUser();

        // Vérifie qu'on a bien un utilisateur (sécurité)
        if (!$user) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour accéder au menu.');
        }

        // Récupère uniquement les cours auxquels le user est inscrit
        $userCourses = $user->getCourses();

        return $this->render('menu.html.twig', [
            'courses' => $userCourses,
        ]);
    }
}
