<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UEController extends AbstractController
{
    #[Route('/ue', name: 'ue')]
    public function index(): Response
    {
        return $this->render('ue/ue.html.twig', [
            'controller_name' => 'UEController',
        ]);
    }
}
