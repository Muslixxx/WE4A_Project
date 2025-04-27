<?php

namespace App\Controller;

use App\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class MenuController extends AbstractController
{
    #[Route('/menu', name: 'app_menu')]
    public function index(EntityManagerInterface $em): Response
    {
        // Récupère l'utilisateur connecté
        $user = $this->getUser();

        if (!$user) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour accéder au menu.');
        }

        // Récupère les cours de l'utilisateur
        $userCourses = $user->getCourses();

        // Récupère les IDs de ces cours
        $courseIds = [];
        foreach ($userCourses as $course) {
            $courseIds[] = $course->getId();
        }

        // Récupère les posts récents liés aux cours de l'utilisateur
        $recentPosts = [];
        if (!empty($courseIds)) {
            $recentPosts = $em->getRepository(Post::class)
                ->createQueryBuilder('p')
                ->join('p.course', 'c')
                ->where('c.id IN (:courseIds)')
                ->setParameter('courseIds', $courseIds)
                ->orderBy('p.dateCreation', 'DESC')
                ->setMaxResults(10)
                ->getQuery()
                ->getResult();
        }

        return $this->render('menu.html.twig', [
            'courses' => $userCourses,
            'recentPosts' => $recentPosts,
        ]);
    }
}
