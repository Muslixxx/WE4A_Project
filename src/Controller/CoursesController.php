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
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Contrôleur principal de gestion des cours (UE) et de leurs contenus associés :
 * - détails des cours
 * - création de posts
 * - gestion des contenus
 * - gestion des statuts de post (important)
 */
class CoursesController extends AbstractController
{
    /**
     * Affiche les détails d’un cours avec ses utilisateurs et ses posts.
     * Tri les posts par importance puis date de création.
     */
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

        // Récupère les utilisateurs inscrits et les trie selon leur rôle
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

        // Rend la vue avec les données collectées
        return $this->render('ue/detail.html.twig', [
            'course' => $course,
            'posts' => $posts,
            'professeurs' => $professeurs,
            'eleves' => $eleves,
        ]);
    }

    /**
     * Création d’un post lié à un cours donné.
     * Seul un utilisateur connecté peut effectuer cette action.
     * Appel depuis JavaScript avec fetch() en AJAX.
     */
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

        // Création du post
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

    /**
     * Supprime un post donné, si l'utilisateur est l’auteur ou a les droits admin.
     */
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

    /**
     * Inverse l’état "important" d’un post.
     * Action réservée aux professeurs et administrateurs.
     */
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

        // Changement de statut
        $post->setIsImportant(!$post->isImportant());

        $em->persist($post);
        $em->flush();

        return new JsonResponse([
            'status' => 'success',
            'isImportant' => $post->isImportant()
        ]);
    }

    /**
     * Crée un contenu (ressource ou document) et l'associe à un cours.
     * Gère les fichiers uploadés localement.
     */
    #[Route('/course/{id}/create-content', name: 'course_create_content', methods: ['POST'])]
    public function createContentForCourse(Request $request, Course $course, EntityManagerInterface $em): JsonResponse
    {
        $user = $this->getUser();

        if (!$user || (!in_array('ROLE_PROF', $user->getRoles()) && !in_array('ROLE_PROF_ADMIN', $user->getRoles()))) {
            return new JsonResponse(['status' => 'error', 'message' => 'Accès refusé.'], 403);
        }

        $name = $request->request->get('name');
        $type = $request->request->get('type');
        $media = $request->request->get('media'); // Pour un lien si type ressource

        /** @var UploadedFile|null $file */
        $file = $request->files->get('file');

        if (!$name || !$type) {
            return new JsonResponse(['status' => 'error', 'message' => 'Champs obligatoires manquants.'], 400);
        }

        $content = new \App\Entity\Content();
        $content->setName($name);
        $content->setType($type);

        // Si c'est un lien
        if ($type === 'ressource') {
            $content->setMedia($media ?: null);
        }

        // Si c'est un fichier à téléverser
        if ($type === 'document' && $file) {
            $uploadDir = $this->getParameter('uploads_directory');
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $safeFilename = uniqid() . '-' . pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $newFilename = $safeFilename . '.' . $file->guessExtension();

            try {
                $file->move($uploadDir, $newFilename);
                $content->setMedia('/uploads/' . $newFilename);
            } catch (FileException $e) {
                return new JsonResponse(['status' => 'error', 'message' => 'Erreur lors de l\'upload.'], 500);
            }
        }

        // Association au cours
        $em->persist($content);
        $course->addContent($content);
        $em->persist($course);
        $em->flush();

        return new JsonResponse(['status' => 'success']);
    }

    /**
     * Supprime un contenu et son fichier local si nécessaire.
     * Gère proprement le détachement de tous les cours liés (relation ManyToMany).
     */
    #[Route('/course/content/{id}/delete', name: 'course_delete_content', methods: ['POST', 'DELETE'])]
    public function deleteContent(\App\Entity\Content $content, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        if (!$user || (!in_array('ROLE_PROF', $user->getRoles()) && !in_array('ROLE_PROF_ADMIN', $user->getRoles()))) {
            throw $this->createAccessDeniedException('Accès refusé.');
        }

        $courses = $content->getCourses();

        if ($courses->isEmpty()) {
            throw $this->createNotFoundException('Ce contenu n\'est associé à aucun cours.');
        }

        // Suppression du fichier physique si applicable
        if ($content->getType() === 'document' && $content->getMedia()) {
            $filePath = $this->getParameter('kernel.project_dir') . '/public' . $content->getMedia();
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        // Suppression de l'association dans tous les cours liés
        foreach ($courses as $course) {
            $course->removeContent($content);
            $em->persist($course);
        }

        $em->remove($content);
        $em->flush();

        // Redirection vers le premier cours lié
        $firstCourse = $courses->first();

        return $this->redirectToRoute('course_detail', ['id' => $firstCourse->getId()]);
    }
}
