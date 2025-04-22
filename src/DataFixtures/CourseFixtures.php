<?php

namespace App\DataFixtures;

use App\Entity\Content;
use App\Entity\Course;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CourseFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $data = [
            'Mathématiques' => [
                'desc' => "Analyse, algèbre et géométrie avancée.",
                'contents' => [
                    ['Vidéo - Dérivées et applications', 'ressource'],
                    ['Exercices corrigés de matrices', 'ressource'],
                    ['Cours complet d’algèbre linéaire', 'document'],
                    ['Formulaire trigonométrie', 'document'],
                ]
            ],
            'Physique' => [
                'desc' => "Mécanique, thermodynamique et électromagnétisme.",
                'contents' => [
                    ['Simulations de chute libre', 'ressource'],
                    ['Cours en vidéo sur l’optique', 'ressource'],
                    ['TD Mécanique - Correction', 'document'],
                    ['Fiche synthèse - Lois de Newton', 'document'],
                ]
            ],
            'Chimie' => [
                'desc' => "Chimie organique, inorganique et analytique.",
                'contents' => [
                    ['Vidéos sur les réactions acido-basiques', 'ressource'],
                    ['Expériences filmées en laboratoire', 'ressource'],
                    ['Fiche sécurité labo', 'document'],
                    ['Résumé chapitre - Liaison chimique', 'document'],
                ]
            ],
            'Informatique' => [
                'desc' => "Programmation, algorithmique et structures de données.",
                'contents' => [
                    ['Playlist de cours Python débutant', 'ressource'],
                    ['Guide sur les structures conditionnelles', 'ressource'],
                    ['TP Java - Gestion des fichiers', 'document'],
                    ['Document - Complexité algorithmique', 'document'],
                ]
            ],
            'Ingénierie' => [
                'desc' => "Conception, innovation et gestion de projets.",
                'contents' => [
                    ['Etudes de cas - Conception produit', 'ressource'],
                    ['Introduction au lean management', 'ressource'],
                    ['Dossier projet - Design Thinking', 'document'],
                    ['Planning Gantt - Outil de suivi', 'document'],
                ]
            ],
            'Anglais' => [
                'desc' => "Grammaire, conversation et littérature.",
                'contents' => [
                    ['Podcast British Council - Listening Skills', 'ressource'],
                    ['Vidéos de conversations', 'ressource'],
                    ['Vocabulaire TOEIC - PDF', 'document'],
                    ['Guide de grammaire anglaise', 'document'],
                ]
            ]
        ];

        foreach ($data as $courseName => $info) {
            $course = new Course();
            $course->setName($courseName);
            $course->setDescription($info['desc']);

            foreach ($info['contents'] as [$title, $type]) {
                $content = new Content();
                $content->setName($title);
                $content->setType($type);
                $manager->persist($content);

                $course->addContent($content);
            }

            $manager->persist($course);
        }

        $manager->flush();
    }
}