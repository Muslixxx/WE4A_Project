<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CoursesController extends AbstractController
{
    #[Route('/cours/mathematiques', name: 'cours_math')]
    public function math(): Response
    {
        $course = [
            'name' => 'Mathématiques',
            'description' => 'Ce cours couvre l’analyse, l’algèbre et la géométrie avancée.',
            'resources' => [
                ['title' => 'Exercices d’algèbre', 'link' => '#'],
                ['title' => 'Vidéo sur la géométrie', 'link' => '#'],
            ],
            'documents' => [
                ['title' => 'Cours de mathématiques PDF', 'link' => '#'],
                ['title' => 'Notes de cours', 'link' => '#'],
            ],
            'posts' => [
                ['date' => '2025-04-01', 'content' => 'Nouveau post sur l’algèbre.'],
                ['date' => '2025-04-03', 'content' => 'Mise à jour du cours sur la géométrie.'],
            ],
            'members' => [
                ['lastName' => 'Dupont', 'firstName' => 'Jean', 'role' => 'Étudiant'],
                ['lastName' => 'Martin', 'firstName' => 'Claire', 'role' => 'Professeur'],
                ['lastName' => 'Durand', 'firstName' => 'Sophie', 'role' => 'Étudiant'],
            ],
        ];

        return $this->render('ue/detail.html.twig', [
            'course' => $course,
        ]);
    }

    #[Route('/cours/physique', name: 'cours_physique')]
    public function physique(): Response
    {
        $course = [
            'name' => 'Physique',
            'description' => 'Ce cours aborde la mécanique, la thermodynamique et l’électromagnétisme.',
            'resources' => [
                ['title' => 'Vidéo sur la mécanique', 'link' => '#'],
            ],
            'documents' => [
                ['title' => 'Guide de thermodynamique', 'link' => '#'],
            ],
            'posts' => [
                ['date' => '2025-03-25', 'content' => 'Correction d’un exercice en physique.'],
            ],
            'members' => [
                ['lastName' => 'Lefèvre', 'firstName' => 'Paul', 'role' => 'Étudiant'],
                ['lastName' => 'Bernard', 'firstName' => 'Marie', 'role' => 'Professeur'],
            ],
        ];

        return $this->render('ue/detail.html.twig', [
            'course' => $course,
        ]);
    }

    #[Route('/cours/chimie', name: 'cours_chimie')]
    public function chimie(): Response
    {
        $course = [
            'name' => 'Chimie',
            'description' => 'Ce cours traite de la chimie organique, inorganique et analytique.',
            'resources' => [
                ['title' => 'Tutoriel sur la chimie organique', 'link' => '#'],
                ['title' => 'Exercices de chimie', 'link' => '#'],
            ],
            'documents' => [
                ['title' => 'Cours de chimie PDF', 'link' => '#'],
            ],
            'posts' => [
                ['date' => '2025-03-20', 'content' => 'Introduction aux réactions chimiques.'],
            ],
            'members' => [
                ['lastName' => 'Moreau', 'firstName' => 'Julien', 'role' => 'Étudiant'],
                ['lastName' => 'Roux', 'firstName' => 'Isabelle', 'role' => 'Professeur'],
                ['lastName' => 'Giraud', 'firstName' => 'Lucie', 'role' => 'Étudiant'],
            ],
        ];

        return $this->render('ue/detail.html.twig', [
            'course' => $course,
        ]);
    }

    #[Route('/cours/informatique', name: 'cours_info')]
    public function informatique(): Response
    {
        $course = [
            'name' => 'Informatique',
            'description' => 'Ce cours aborde la programmation, l’algorithmique et les structures de données.',
            'resources' => [
                ['title' => 'Tutoriel PHP', 'link' => '#'],
                ['title' => 'Exercices de programmation', 'link' => '#'],
            ],
            'documents' => [
                ['title' => 'Guide d’algorithmique', 'link' => '#'],
            ],
            'posts' => [
                ['date' => '2025-04-05', 'content' => 'Nouveau module sur les algorithmes.'],
            ],
            'members' => [
                ['lastName' => 'Petit', 'firstName' => 'Alice', 'role' => 'Étudiant'],
                ['lastName' => 'Lemoine', 'firstName' => 'Marc', 'role' => 'Professeur'],
                ['lastName' => 'Durand', 'firstName' => 'Thomas', 'role' => 'Étudiant'],
            ],
        ];

        return $this->render('ue/detail.html.twig', [
            'course' => $course,
        ]);
    }

    #[Route('/cours/ingenierie', name: 'cours_inge')]
    public function ingenierie(): Response
    {
        $course = [
            'name' => 'Ingénierie',
            'description' => 'Ce cours couvre la conception, l’innovation et la gestion de projets.',
            'resources' => [
                ['title' => 'Exemples de projets', 'link' => '#'],
            ],
            'documents' => [
                ['title' => 'Plan de projet', 'link' => '#'],
            ],
            'posts' => [
                ['date' => '2025-03-30', 'content' => 'Discussion sur l\'innovation technologique.'],
            ],
            'members' => [
                ['lastName' => 'Mercier', 'firstName' => 'Sébastien', 'role' => 'Étudiant'],
                ['lastName' => 'Morel', 'firstName' => 'Camille', 'role' => 'Professeur'],
            ],
        ];

        return $this->render('ue/detail.html.twig', [
            'course' => $course,
        ]);
    }

    #[Route('/cours/anglais', name: 'cours_anglais')]
    public function anglais(): Response
    {
        $course = [
            'name' => 'Anglais',
            'description' => 'Ce cours aborde la grammaire, la conversation et la littérature anglaise.',
            'resources' => [
                ['title' => 'Cours de grammaire', 'link' => '#'],
                ['title' => 'Podcast en anglais', 'link' => '#'],
            ],
            'documents' => [
                ['title' => 'Guide de conversation', 'link' => '#'],
            ],
            'posts' => [
                ['date' => '2025-04-07', 'content' => 'Discussion sur la littérature anglaise.'],
            ],
            'members' => [
                ['lastName' => 'Girard', 'firstName' => 'Emma', 'role' => 'Étudiant'],
                ['lastName' => 'Blanc', 'firstName' => 'Antoine', 'role' => 'Professeur'],
            ],
        ];

        return $this->render('ue/detail.html.twig', [
            'course' => $course,
        ]);
    }
}
