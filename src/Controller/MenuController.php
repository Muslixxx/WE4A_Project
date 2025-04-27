<?php

namespace App\Controller;

use App\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

class MenuController extends AbstractController
{
    #[Route('/menu', name: 'app_menu')]
    public function index(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        if (!$user) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour accéder au menu.');
        }

        $userCourses = $user->getCourses();
        $courseIds = [];

        foreach ($userCourses as $course) {
            $courseIds[] = $course->getId();
        }

        $recentPosts = [];
        if (!empty($courseIds)) {
            $recentPosts = $em->getRepository(Post::class)
                ->createQueryBuilder('p')
                ->join('p.course', 'c')
                ->where('c.id IN (:courseIds)')
                ->setParameter('courseIds', $courseIds)
                ->orderBy('p.pinned', 'DESC')    // 🔥 1. d'abord épinglés
                ->addOrderBy('p.dateCreation', 'DESC') // 🔥 2. ensuite date
                ->setMaxResults(3)
                ->getQuery()
                ->getResult();


        }

        return $this->render('menu.html.twig', [
            'courses' => $userCourses,
            'recentPosts' => $recentPosts,
        ]);
    }

    #[Route('/menu/load-more-posts', name: 'menu_load_more_posts', methods: ['GET'])]
    public function loadMorePosts(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        if (!$user) {
            throw $this->createAccessDeniedException('Vous devez être connecté.');
        }

        $userCourses = $user->getCourses();
        $courseIds = [];

        foreach ($userCourses as $course) {
            $courseIds[] = $course->getId();
        }

        $posts = [];
        if (!empty($courseIds)) {
            $posts = $em->getRepository(Post::class)
                ->createQueryBuilder('p')
                ->join('p.course', 'c')
                ->where('c.id IN (:courseIds)')
                ->setParameter('courseIds', $courseIds)
                ->orderBy('p.pinned', 'DESC')
                ->addOrderBy('p.dateCreation', 'DESC')
                ->getQuery()
                ->getResult();
        }

        // Préparer un tableau JSON
        $data = [];
        foreach ($posts as $post) {
            $data[] = [
                'date' => $post->getDateCreation()->format('d/m/Y H:i'),
                'firstName' => $post->getUser()->getFirstName(),
                'lastName' => $post->getUser()->getLastName(),
                'type' => $post->getType(),
                'title' => $post->getTitle(),
                'courseName' => $post->getCourse()->getName(),
            ];
        }

        return $this->json($data);
    }
}
