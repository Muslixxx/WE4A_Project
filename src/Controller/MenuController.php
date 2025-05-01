<?php

namespace App\Controller;

use App\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Contrôleur responsable de l'affichage du menu principal de l'application.
 * Il permet à l'utilisateur connecté de voir ses cours et les posts récents associés.
 */
class MenuController extends AbstractController
{
    /**
     * Affiche la page principale du menu avec la liste des cours et
     * les 3 posts les plus récents (ou épinglés) liés aux cours de l'utilisateur.
     */
    #[Route('/menu', name: 'app_menu')]
    public function index(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        // Vérifie que l'utilisateur est bien authentifié
        if (!$user) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour accéder au menu.');
        }

        // Récupère tous les cours associés à l'utilisateur connecté
        $userCourses = $user->getCourses();
        $courseIds = [];

        foreach ($userCourses as $course) {
            $courseIds[] = $course->getId();
        }

        // Recherche des posts récents et épinglés liés aux cours
        $recentPosts = [];
        if (!empty($courseIds)) {
            $recentPosts = $em->getRepository(Post::class)
                ->createQueryBuilder('p')
                ->join('p.course', 'c')
                ->where('c.id IN (:courseIds)')
                ->setParameter('courseIds', $courseIds)
                ->orderBy('p.isImportant', 'DESC')       // Les posts épinglés en premier
                ->addOrderBy('p.dateCreation', 'DESC')   // Puis les plus récents
                ->setMaxResults(3)                       // Limité à 3 posts ici
                ->getQuery()
                ->getResult();
        }

        // Rend la vue de menu.html.twig avec les cours et les posts filtrés
        return $this->render('menu.html.twig', [
            'courses' => $userCourses,
            'recentPosts' => $recentPosts,
        ]);
    }

    /**
     * Charge dynamiquement tous les posts liés aux cours de l'utilisateur (utilisé via AJAX).
     * Renvoie un tableau JSON formaté.
     */
    #[Route('/menu/load-more-posts', name: 'menu_load_more_posts', methods: ['GET'])]
    public function loadMorePosts(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        if (!$user) {
            throw $this->createAccessDeniedException('Vous devez être connecté.');
        }

        // Identifie les cours liés à l'utilisateur
        $userCourses = $user->getCourses();
        $courseIds = [];

        foreach ($userCourses as $course) {
            $courseIds[] = $course->getId();
        }

        // Récupère tous les posts des cours de l'utilisateur
        $posts = [];
        if (!empty($courseIds)) {
            $posts = $em->getRepository(Post::class)
                ->createQueryBuilder('p')
                ->join('p.course', 'c')
                ->where('c.id IN (:courseIds)')
                ->setParameter('courseIds', $courseIds)
                ->orderBy('p.isImportant', 'DESC')
                ->addOrderBy('p.dateCreation', 'DESC')
                ->getQuery()
                ->getResult();
        }

        // Formatage des données en JSON pour affichage dynamique
        $data = [];
        foreach ($posts as $post) {
            $data[] = [
                'date' => $post->getDateCreation()->format('d/m/Y H:i'),
                'firstName' => $post->getUser()->getFirstName(),
                'lastName' => $post->getUser()->getLastName(),
                'title' => $post->getTitle(),
                'courseName' => $post->getCourse()->getName(),
            ];
        }

        return $this->json($data);
    }
}
