<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ExempleController extends AbstractController
{
    #[Route('/exemple', name: 'app_exemple')]
    public function index(RequestStack $requestStack): Response
    {
        $session = $requestStack->getSession();

        // Écriture
        $session->set('nom_utilisateur', 'Tophe');

        // Lecture
        $nom = $session->get('nom_utilisateur');

        return new Response("Nom utilisateur en session : $nom");
    }
}
