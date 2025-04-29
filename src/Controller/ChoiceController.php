<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Contrôleur qui gère la page de sélection pour les utilisateurs avec les rôles prof et admin.
 */
class ChoiceController extends AbstractController
{
    #[Route('/choice', name: 'app_choice')]
    public function choice(): Response
    {
        return $this->render('choice.html.twig');
    }
}
