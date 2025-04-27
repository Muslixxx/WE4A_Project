<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Repository\CourseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    #[IsGranted('ROLE_ADMIN')]
    public function index(UserRepository $userRepository, CourseRepository $courseRepository): Response
    {
        $users = $userRepository->findAll();
        $courses = $courseRepository->findAll();

        return $this->render('admin/admin.html.twig', [
            'users' => $users,
            'courses' => $courses,
        ]);
    }
}

//neuille