<?php

namespace App\Controller;

use App\Entity\Course;
use App\Repository\CourseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UESelectionController extends AbstractController
{
    #[Route('/ue-selection', name: 'ue_selection', methods: ['GET', 'POST'])]
    public function index(Request $request, CourseRepository $courseRepository, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        if ($request->isMethod('POST')) {
            $courseId = $request->request->getInt('course_id');
            $action = $request->request->get('action');
            $course = $courseRepository->find($courseId);

            if ($course && $user) {
                // Correction ici
                if (in_array($user->getRole(), ['ROLE_PROF', 'ROLE_PROF_ADMIN'])) {
                    $maxUE = 3;
                } else {
                    $maxUE = 5;
                }

                if ($action === 'add') {
                    if (!$user->getCourses()->contains($course) && count($user->getCourses()) < $maxUE) {
                        $user->addCourse($course);
                        $em->persist($user);
                        $em->flush();
                    }
                } elseif ($action === 'remove' && $user->getCourses()->contains($course)) {
                    $user->removeCourse($course);
                    $em->persist($user);
                    $em->flush();
                }
            }

            return $this->redirectToRoute('ue_selection');
        }

        $courses = $courseRepository->findAll();

        return $this->render('ue/selection.html.twig', [
            'courses' => $courses,
            'userCourses' => $user?->getCourses() ?? [],
        ]);
    }
}
