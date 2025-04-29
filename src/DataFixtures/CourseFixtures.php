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
                    ['Vidéo - Dérivées et applications', 'ressource', 'https://youtube.com/dérivées'],
                    ['Exercices corrigés de matrices', 'ressource', null],
                    ['Cours complet d\'algèbre linéaire', 'document', null],
                    ['Formulaire trigonométrie', 'document', null],
                    ['Chaîne YouTube Maths', 'ressource', 'https://youtube.com/'],
                ]
            ],
            'Physique' => [
                'desc' => "Mécanique, thermodynamique et électromagnétisme.",
                'contents' => [
                    ['Cours en vidéo sur l\'optique', 'ressource', null],
                    ['TD Mécanique - Correction', 'document', null],
                    ['Fiche synthèse - Lois de Newton', 'document', null],
                    ['Chaîne YouTube Maths', 'ressource', 'https://youtube.com/'],
                ]
            ],
            'Chimie' => [
                'desc' => "Chimie organique, inorganique et analytique.",
                'contents' => [
                    ['Expériences filmées en laboratoire', 'ressource', null],
                    ['Fiche sécurité labo', 'document', null],
                    ['Résumé chapitre - Liaison chimique', 'document', null],
                    ['Chaîne YouTube chimie', 'ressource', 'https://youtube.com/'],
                ]
            ],
            'Informatique' => [
                'desc' => "Programmation, algorithmique et structures de données.",
                'contents' => [
                    ['Guide sur les structures conditionnelles', 'ressource', null],
                    ['TP Java - Gestion des fichiers', 'document', null],
                    ['Document - Complexité algorithmique', 'document', null],
                    ['Chaîne YouTube info', 'ressource', 'https://youtube.com/'],
                ]
            ],
            'Ingénierie' => [
                'desc' => "Conception, innovation et gestion de projets.",
                'contents' => [
                    ['Études de cas - Conception produit', 'ressource', null],
                    ['Dossier projet - Design Thinking', 'document', null],
                    ['Planning Gantt - Outil de suivi', 'document', null],
                    ['Chaîne YouTube ingénieur', 'ressource', 'https://youtube.com/'],
                ]
            ],
            'Anglais' => [
                'desc' => "Grammaire, conversation et littérature.",
                'contents' => [
                    ['Podcast British Council - Listening Skills', 'ressource', 'https://learnenglish.britishcouncil.org'],
                    ['Vidéos de conversations', 'ressource', null],
                    ['Vocabulaire TOEIC - PDF', 'document', null],
                    ['Guide de grammaire anglaise', 'document', null],
                    ['Chaîne YouTube en anglais', 'ressource', 'https://youtube.com/'],
                ]
            ]
        ];

        foreach ($data as $courseName => $info) {
            $course = new Course();
            $course->setName($courseName);
            $course->setDescription($info['desc']);

            foreach ($info['contents'] as [$title, $type, $media]) {
                $content = new Content();
                $content->setName($title);
                $content->setType($type);
                if ($media !== null) {
                    $content->setMedia($media);
                }

                $manager->persist($content);
                $course->addContent($content);
            }

            $manager->persist($course);
        }

        $manager->flush();
    }
}
