<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\Course;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class PostController extends AbstractController
{
    #[Route('/post/create', name: 'post_create', methods: ['POST'])]
    public function createPost(Request $request, EntityManagerInterface $em, Security $security): JsonResponse
    {
        $user = $security->getUser();
        if (!$user) {
            return new JsonResponse(['error' => 'Utilisateur non connecté'], 403);
        }

        $data = json_decode($request->getContent(), true);

        // Vérifie les champs obligatoires
        if (!isset($data['title'], $data['description'], $data['type'], $data['is_important'])) {
            return new JsonResponse(['error' => 'Champs manquants'], 400);
        }

        // ⚠️ Récupérer le cours actif
        // Solution 1 : envoyer le courseId via JavaScript
        $courseId = $request->query->get('courseId');
        if (!$courseId) {
            return new JsonResponse(['error' => 'courseId manquant'], 400);
        }

        $course = $em->getRepository(Course::class)->find($courseId);
        if (!$course) {
            return new JsonResponse(['error' => 'UE non trouvée'], 404);
        }

        // Création du post
        $post = new Post();
        $post->setTitle($data['title']);
        $post->setDescription($data['description']);
        $post->setType($data['type']);
        $post->setIsImportant((bool)$data['is_important']);
        $post->setDateCreation(new \DateTimeImmutable());
        $post->setUser($user);
        $post->setCourse($course);

        $em->persist($post);
        $em->flush();

        return new JsonResponse(['status' => 'success']);
    }
}
