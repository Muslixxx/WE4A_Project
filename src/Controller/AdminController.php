<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Course;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    #[IsGranted('ROLE_ADMIN')]
    public function index(EntityManagerInterface $em): Response
    {
        $users = $em->getRepository(User::class)->findAll();
        $courses = $em->getRepository(Course::class)->findAll();

        return $this->render('admin/admin.html.twig', [
            'users' => $users,
            'courses' => $courses,
        ]);
    }

    #[Route('/admin/delete-user/{id}', name: 'admin_delete_user', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function deleteUser(int $id, EntityManagerInterface $em): JsonResponse
    {
        $user = $em->getRepository(User::class)->find($id);

        if (!$user) {
            return new JsonResponse(['status' => 'error', 'message' => 'Utilisateur non trouvé.'], 404);
        }

        $em->remove($user);
        $em->flush();

        return new JsonResponse(['status' => 'success']);
    }

    #[Route('/admin/delete-course/{id}', name: 'admin_delete_course', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function deleteCourse(int $id, EntityManagerInterface $em): JsonResponse
    {
        $course = $em->getRepository(Course::class)->find($id);

        if (!$course) {
            return new JsonResponse(['status' => 'error', 'message' => 'Cours non trouvé.'], 404);
        }

        $em->remove($course);
        $em->flush();

        return new JsonResponse(['status' => 'success']);
    }
}
