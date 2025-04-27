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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
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

    #[Route('/admin/update-user/{id}', name: 'admin_update_user', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function updateUser(
        int $id,
        Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $passwordHasher
    ): JsonResponse {
        $user = $em->getRepository(User::class)->find($id);

        if (!$user) {
            return new JsonResponse(['status' => 'error', 'message' => 'Utilisateur non trouvé.'], 404);
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data['email'])) {
            $user->setEmail($data['email']);
        }
        if (isset($data['firstName'])) {
            $user->setFirstName($data['firstName']);
        }
        if (isset($data['lastName'])) {
            $user->setLastName($data['lastName']);
        }
        if (isset($data['phoneNumber'])) {
            $user->setPhoneNumber($data['phoneNumber']);
        }
        if (isset($data['birthDate']) && !empty($data['birthDate'])) {
            try {
                $birthDate = new \DateTime($data['birthDate']);
                $user->setBirthDate($birthDate);
            } catch (\Exception $e) {
                return new JsonResponse(['status' => 'error', 'message' => 'Date de naissance invalide.'], 400);
            }
        }
        if (isset($data['role'])) {
            $user->setRole($data['role']);
        }
        if (!empty($data['password'])) {
            $hashedPassword = $passwordHasher->hashPassword($user, $data['password']);
            $user->setPassword($hashedPassword);
        }

        $em->flush();

        return new JsonResponse(['status' => 'success']);
    }

}
