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
                ['lastName' => 'Petit', 'firstName' => 'Lucas', 'role' => 'Étudiant'],
                ['lastName' => 'Robert', 'firstName' => 'Emma', 'role' => 'Étudiant'],
                ['lastName' => 'Lefevre', 'firstName' => 'Hugo', 'role' => 'Professeur'],
                ['lastName' => 'Moreau', 'firstName' => 'Chloé', 'role' => 'Étudiant'],
                ['lastName' => 'Simon', 'firstName' => 'Nathan', 'role' => 'Étudiant'],
                ['lastName' => 'Baron', 'firstName' => 'Noémie', 'role' => 'Étudiant'],
                ['lastName' => 'Rolland', 'firstName' => 'Evan', 'role' => 'Étudiant'],
                ['lastName' => 'Texier', 'firstName' => 'Iris', 'role' => 'Professeur'],
                ['lastName' => 'Guillaume', 'firstName' => 'Aaron', 'role' => 'Étudiant'],
                ['lastName' => 'Bouchet', 'firstName' => 'Clara', 'role' => 'Étudiant'],
                ['lastName' => 'Schmitt', 'firstName' => 'Valentin', 'role' => 'Professeur'],
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
                ['lastName' => 'Laurent', 'firstName' => 'Camille', 'role' => 'Professeur'],
                ['lastName' => 'Michel', 'firstName' => 'Léo', 'role' => 'Étudiant'],
                ['lastName' => 'Garcia', 'firstName' => 'Inès', 'role' => 'Étudiant'],
                ['lastName' => 'Bernard', 'firstName' => 'Tom', 'role' => 'Professeur'],
                ['lastName' => 'Roux', 'firstName' => 'Manon', 'role' => 'Étudiant'],
                ['lastName' => 'David', 'firstName' => 'Noah', 'role' => 'Professeur'],
                ['lastName' => 'Bertrand', 'firstName' => 'Anna', 'role' => 'Étudiant'],
                ['lastName' => 'Morel', 'firstName' => 'Clément', 'role' => 'Étudiant'],
                ['lastName' => 'Philippe', 'firstName' => 'Mathéo', 'role' => 'Étudiant'],
                ['lastName' => 'Renaud', 'firstName' => 'Lola', 'role' => 'Étudiant'],
                ['lastName' => 'Michaud', 'firstName' => 'Axelle', 'role' => 'Professeur'],
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
                ['lastName' => 'Fournier', 'firstName' => 'Jade', 'role' => 'Étudiant'],
                ['lastName' => 'Girard', 'firstName' => 'Ethan', 'role' => 'Professeur'],
                ['lastName' => 'Bonnet', 'firstName' => 'Sarah', 'role' => 'Étudiant'],
                ['lastName' => 'Lambert', 'firstName' => 'Arthur', 'role' => 'Étudiant'],
                ['lastName' => 'Fontaine', 'firstName' => 'Mila', 'role' => 'Professeur'],
                ['lastName' => 'Rousseau', 'firstName' => 'Axel', 'role' => 'Étudiant'],
                ['lastName' => 'Blanc', 'firstName' => 'Zoé', 'role' => 'Étudiant'],
                ['lastName' => 'Henry', 'firstName' => 'Mathis', 'role' => 'Professeur'],
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
                ['lastName' => 'Lucas', 'firstName' => 'Léna', 'role' => 'Étudiant'],
                ['lastName' => 'Masson', 'firstName' => 'Eliott', 'role' => 'Étudiant'],
                ['lastName' => 'Barbier', 'firstName' => 'Julia', 'role' => 'Professeur'],
                ['lastName' => 'Renard', 'firstName' => 'Louis', 'role' => 'Étudiant'],
                ['lastName' => 'Garnier', 'firstName' => 'Élise', 'role' => 'Étudiant'],
                ['lastName' => 'Chevalier', 'firstName' => 'Noé', 'role' => 'Professeur'],
                ['lastName' => 'Faure', 'firstName' => 'Margaux', 'role' => 'Étudiant'],
                ['lastName' => 'Lopez', 'firstName' => 'Adam', 'role' => 'Étudiant'],
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
                ['lastName' => 'Marchand', 'firstName' => 'Nina', 'role' => 'Professeur'],
                ['lastName' => 'Renaud', 'firstName' => 'Enzo', 'role' => 'Étudiant'],
                ['lastName' => 'Morin', 'firstName' => 'Alice', 'role' => 'Étudiant'],
                ['lastName' => 'Joly', 'firstName' => 'Timéo', 'role' => 'Professeur'],
                ['lastName' => 'Gauthier', 'firstName' => 'Lola', 'role' => 'Étudiant'],
                ['lastName' => 'Perrot', 'firstName' => 'Thomas', 'role' => 'Étudiant'],
                ['lastName' => 'Lemoine', 'firstName' => 'Eva', 'role' => 'Professeur'],
                ['lastName' => 'Perrin', 'firstName' => 'Gabriel', 'role' => 'Étudiant'],
                ['lastName' => 'Henry', 'firstName' => 'Jade', 'role' => 'Étudiant'],
                ['lastName' => 'Leroux', 'firstName' => 'Héloïse', 'role' => 'Professeur'],
                ['lastName' => 'Lemoine', 'firstName' => 'Quentin', 'role' => 'Étudiant'],
                ['lastName' => 'Hubert', 'firstName' => 'Salomé', 'role' => 'Étudiant'],
                ['lastName' => 'Pascal', 'firstName' => 'Baptiste', 'role' => 'Professeur'],
                ['lastName' => 'Devaux', 'firstName' => 'Louna', 'role' => 'Étudiant'],
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
                ['lastName' => 'Benoit', 'firstName' => 'Jeanne', 'role' => 'Étudiant'],
                ['lastName' => 'Marty', 'firstName' => 'Alexis', 'role' => 'Professeur'],
                ['lastName' => 'Collet', 'firstName' => 'Naël', 'role' => 'Étudiant'],
                ['lastName' => 'Meyer', 'firstName' => 'Léna', 'role' => 'Étudiant'],
                ['lastName' => 'Leclerc', 'firstName' => 'Maël', 'role' => 'Professeur'],
                ['lastName' => 'Da Silva', 'firstName' => 'Lou', 'role' => 'Étudiant'],
                ['lastName' => 'Legrand', 'firstName' => 'Raphaël', 'role' => 'Étudiant'],
                ['lastName' => 'Vidal', 'firstName' => 'Jasmine', 'role' => 'Professeur'],
                ['lastName' => 'Lopez', 'firstName' => 'Maxime', 'role' => 'Étudiant'],
                ['lastName' => 'Delattre', 'firstName' => 'Amandine', 'role' => 'Professeur'],
                ['lastName' => 'Albert', 'firstName' => 'Maé', 'role' => 'Étudiant'],
                ['lastName' => 'Charles', 'firstName' => 'Émile', 'role' => 'Étudiant'],
                ['lastName' => 'Bourdon', 'firstName' => 'Nina', 'role' => 'Professeur'],
            ],
        ];

        return $this->render('ue/detail.html.twig', [
            'course' => $course,
        ]);
    }
}
