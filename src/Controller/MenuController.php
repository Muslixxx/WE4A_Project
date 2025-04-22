<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CourseRepository;


class MenuController extends AbstractController
{
    #[Route('/menu', name: 'app_menu')]
    public function index(CourseRepository $courseRepository): Response
    {
        $courses = $courseRepository->findAll(); // récupère tous les cours en base

        return $this->render('menu.html.twig', [
            'courses' => $courses,
        ]);
    }
}
