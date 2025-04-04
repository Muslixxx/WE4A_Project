<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UEController extends AbstractController
{
    #[Route('/cours/{id}', name: 'cours_detail')]
    public function show(int $id): Response
    {
        // Tu pourras plus tard récupérer le cours depuis la base
        return $this->render('UE.html.twig', [
            'id' => $id,
        ]);
    }
}
