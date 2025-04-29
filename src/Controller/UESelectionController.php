<?php

namespace App\Controller;

use App\Repository\CourseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Contrôleur permettant à un utilisateur (élève ou prof) de sélectionner ses UE.
 * Il peut ajouter ou retirer un cours à sa liste personnelle, selon un quota dépendant de son rôle.
 */
class UESelectionController extends AbstractController
{
    /**
     * Gère l'affichage de la liste des UE disponibles et aussi le traitement
     * des ajouts et des suppressions à la liste personnelle de l'utilisateur connecté.
     *
     * - GET : affiche la page avec les cours disponibles
     * - POST : traite l'ajout ou la suppression d'un cours
     */
    #[Route('/ue-selection', name: 'ue_selection', methods: ['GET', 'POST'])]
    public function index(Request $request, CourseRepository $courseRepository, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        // Si requête POST : l'utilisateur a soumis un ajout ou une suppression
        if ($request->isMethod('POST')) {
            $courseId = $request->request->getInt('course_id'); // ID de l'UE sélectionnée
            $action = $request->request->get('action');         // 'add' ou 'remove'
            $course = $courseRepository->find($courseId);

            if ($course && $user) {
                // Définition de la limite d'UE selon le rôle utilisateur
                if (in_array($user->getRole(), ['ROLE_PROF', 'ROLE_PROF_ADMIN'])) {
                    $maxUE = 3; // Professeur : max 3 UE
                } else {
                    $maxUE = 5; // Élève : max 5 UE
                }

                if ($action === 'add') {
                    // Ajout uniquement si non déjà inscrit et quota non dépassé
                    if (!$user->getCourses()->contains($course) && count($user->getCourses()) < $maxUE) {
                        $user->addCourse($course);
                        $em->persist($user);
                        $em->flush();
                    }
                } elseif ($action === 'remove' && $user->getCourses()->contains($course)) {
                    // Suppression uniquement si l'utilisateur est déjà inscrit à ce cours
                    $user->removeCourse($course);
                    $em->persist($user);
                    $em->flush();
                }
            }

            // Redirection après POST (évite le double envoi si on rafraîchit la page)
            return $this->redirectToRoute('ue_selection');
        }

        // Requête GET : affichage de tous les cours disponibles
        $courses = $courseRepository->findAll();

        return $this->render('ue/selection.html.twig', [
            'courses' => $courses,                               // Liste complète des UE
            'userCourses' => $user?->getCourses() ?? [],         // Cours auxquels l'utilisateur est inscrit
        ]);
    }
}
