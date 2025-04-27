<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ChoiceController extends AbstractController
{
    #[Route('/choice', name: 'app_choice')]
    public function choice(): Response
    {
        // votre logique, puis :
        return $this->render('choice.html.twig');
    }
}
