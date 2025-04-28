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
            ->orderBy('p.isImportant', 'DESC')
            ->addOrderBy('p.dateCreation', 'DESC')
            ->getQuery()
            ->getResult();

        $users = $course->getUsers();

        $professeurs = [];
        $eleves = [];

        foreach ($users as $user) {
            if (in_array($user->getRole(), ['ROLE_PROF', 'ROLE_PROF_ADMIN'])) {
                $professeurs[] = $user;
            } elseif ($user->getRole() === 'ROLE_ELEVE') {
                $eleves[] = $user;
            }
        }

        return $this->render('ue/detail.html.twig', [
            'course' => $course,
            'posts' => $posts,
            'professeurs' => $professeurs,
            'eleves' => $eleves,
        ]);
    }

    #[Route('/course/create-post/{id}', name: 'course_create_post', methods: ['POST'])]
    public function createPostForCourse(Request $request, Course $course, EntityManagerInterface $em): JsonResponse
    {
        $user = $this->getUser();
        if (!$user) {
            return new JsonResponse(['status' => 'error', 'message' => 'Utilisateur non connecté.'], 403);
        }

        if (!$request->isXmlHttpRequest()) {
            return new JsonResponse(['status' => 'error', 'message' => 'Requête non valide.'], 400);
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

    #[Route('/course/post/{id}/delete', name: 'course_delete_post', methods: ['POST', 'DELETE'])]
    public function deletePost(Post $post, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        if (!$user || ($user !== $post->getUser() && !in_array('ROLE_PROF_ADMIN', $user->getRoles()))) {
            throw $this->createAccessDeniedException('Vous n\'êtes pas autorisé à supprimer ce post.');
        }

        $courseId = $post->getCourse()->getId();

        $em->remove($post);
        $em->flush();

        return $this->redirectToRoute('course_detail', ['id' => $courseId]);
    }

    #[Route('/course/post/{id}/toggle-important', name: 'course_toggle_important', methods: ['POST'])]
    public function toggleImportantPost(Request $request, Post $post, EntityManagerInterface $em): JsonResponse
    {
        $user = $this->getUser();
        if (!$user || (!in_array('ROLE_PROF', $user->getRoles()) && !in_array('ROLE_PROF_ADMIN', $user->getRoles()))) {
            return new JsonResponse(['status' => 'error', 'message' => 'Accès refusé.'], 403);
        }

        if (!$request->isXmlHttpRequest()) {
            return new JsonResponse(['status' => 'error', 'message' => 'Requête non valide.'], 400);
        }

        $post->setIsImportant(!$post->isImportant());

        $em->persist($post);
        $em->flush();

        return new JsonResponse([
            'status' => 'success',
            'isImportant' => $post->isImportant()
        ]);
    }
}
