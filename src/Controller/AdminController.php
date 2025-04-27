<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Course;
use App\Entity\Post;
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

        // Charger seulement les 3 dernières activités
        $recentPosts = $em->getRepository(Post::class)
            ->createQueryBuilder('p')
            ->orderBy('p.pinned', 'DESC')    // 🔥 d'abord épinglés
            ->addOrderBy('p.dateCreation', 'DESC') // 🔥 ensuite date
            ->setMaxResults(3)
            ->getQuery()
            ->getResult();


        return $this->render('admin/admin.html.twig', [
            'users' => $users,
            'courses' => $courses,
            'recentPosts' => $recentPosts,
        ]);
    }

    #[Route('/admin/load-more-posts', name: 'admin_load_more_posts', methods: ['GET'])]
    public function loadMorePosts(EntityManagerInterface $em): JsonResponse
    {
        $posts = $em->getRepository(Post::class)
            ->createQueryBuilder('p')
            ->orderBy('p.dateCreation', 'DESC')
            ->getQuery()
            ->getResult();

        $data = [];
        foreach ($posts as $post) {
            $data[] = [
                'date' => $post->getDateCreation()->format('d/m/Y H:i'),
                'firstName' => $post->getUser()->getFirstName(),
                'lastName' => $post->getUser()->getLastName(),
                'type' => $post->getType(),
                'title' => $post->getTitle(),
                'courseName' => $post->getCourse()->getName(),
                'pinned' => $post->isPinned() ? true : false,
            ];
        }

        return $this->json($data);
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
            return new JsonResponse(['status' => 'error', 'message' => 'UE non trouvée.'], 404);
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

    #[Route('/admin/create-user', name: 'admin_create_user', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function createUser(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!$data) {
            return new JsonResponse(['status' => 'error', 'message' => 'Données invalides.'], 400);
        }

        $user = new User();
        $user->setEmail($data['email']);
        $user->setFirstName($data['firstName']);
        $user->setLastName($data['lastName']);
        $user->setPhoneNumber($data['phoneNumber']);
        $user->setBirthDate(new \DateTime($data['birthDate']));
        $user->setRole($data['role']);
        $user->setDateCreation(new \DateTime());

        $hashedPassword = $passwordHasher->hashPassword($user, $data['password']);
        $user->setPassword($hashedPassword);

        $em->persist($user);
        $em->flush();

        return new JsonResponse(['status' => 'success']);
    }

    #[Route('/admin/create-course', name: 'admin_create_course', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function createCourse(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!$data || empty($data['name']) || empty($data['description'])) {
            return new JsonResponse(['status' => 'error', 'message' => 'Données invalides.'], 400);
        }

        $course = new Course();
        $course->setName($data['name']);
        $course->setDescription($data['description']);

        $em->persist($course);
        $em->flush();

        return new JsonResponse(['status' => 'success']);
    }

    #[Route('/admin/update-course/{id}', name: 'admin_update_course', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function updateCourse(int $id, Request $request, EntityManagerInterface $em): JsonResponse
    {
        $course = $em->getRepository(Course::class)->find($id);

        if (!$course) {
            return new JsonResponse(['status' => 'error', 'message' => 'UE non trouvée.'], 404);
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data['name'])) {
            $course->setName($data['name']);
        }
        if (isset($data['description'])) {
            $course->setDescription($data['description']);
        }

        $em->flush();

        return new JsonResponse(['status' => 'success']);
    }
}
