<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UESelectionController extends AbstractController
{
    #[Route('/ue-selection', name: 'ue_selection')]
    public function index(): Response
    {
        // Liste des matières (simulées)
        $subjects = [
            'mathematics' => 'Mathématiques',
            'computer_science' => 'Informatique',
            'physics' => 'Physique',
            'chemistry' => 'Chimie',
            'biology' => 'Biologie',
            'history' => 'Histoire'
        ];

        return $this->render('ue/selection.html.twig', [
            'subjects' => $subjects,
        ]);
    }
}
