<?php

namespace App\Controller;

use App\Entity\Course;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CoursesController extends AbstractController
{
    #[Route('/cours/{id}', name: 'course_detail')]
    public function courseDetail(Course $course): Response
    {
        return $this->render('ue/detail.html.twig', [
            'course' => $course,
        ]);
    }
}
