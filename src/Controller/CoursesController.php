<?php

namespace App\Controller;

use App\Entity\Course;
use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CoursesController extends AbstractController
{
    #[Route('/course/{id}', name: 'course_detail', methods: ['GET'])]
    public function courseDetail(Course $course, EntityManagerInterface $em): Response
    {
        $posts = $em->getRepository(Post::class)
            ->createQueryBuilder('p')
            ->where('p.course = :course')
            ->setParameter('course', $course)
            ->orderBy('p.pinned', 'DESC')
            ->addOrderBy('p.dateCreation', 'DESC')
            ->getQuery()
            ->getResult();

        return $this->render('ue/detail.html.twig', [
            'course' => $course,
            'posts' => $posts,
        ]);
    }

    #[Route('/course/create-post/{id}', name: 'course_create_post', methods: ['POST'])]
    public function createPostForCourse(Request $request, Course $course, EntityManagerInterface $em): JsonResponse
    {
        $user = $this->getUser();
        if (!$user) {
            return new JsonResponse(['status' => 'error', 'message' => 'Utilisateur non connecté.'], 403);
        }

        $data = json_decode($request->getContent(), true);

        if (!$data || !isset($data['title'], $data['description'], $data['type'], $data['is_important'])) {
            return new JsonResponse(['status' => 'error', 'message' => 'Données invalides.'], 400);
        }

        $post = new Post();
        $post->setTitle($data['title']);
        $post->setDescription($data['description']);
        $post->setType($data['type']);
        $post->setIsImportant((bool) $data['is_important']);
        $post->setDateCreation(new \DateTimeImmutable());
        $post->setUser($user);
        $post->setCourse($course);

        $em->persist($post);
        $em->flush();

        return new JsonResponse(['status' => 'success']);
    }
    #[Route('/course/pin-post/{id}', name: 'course_toggle_pin_post', methods: ['POST'])]
    public function togglePinPost(Post $post, EntityManagerInterface $em): JsonResponse
    {
        // Vérifie que seul les profs peuvent épingler
        $user = $this->getUser();
        if (!$user || !in_array($user->getRole(), ['ROLE_PROF', 'ROLE_PROF_ADMIN'])) {
            return new JsonResponse(['status' => 'error', 'message' => 'Accès refusé.'], 403);
        }

        $post->setPinned(!$post->isPinned());
        $em->flush();

        return new JsonResponse(['status' => 'success']);
    }

}
