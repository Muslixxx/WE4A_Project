<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 *
 * Fournit le changement de mot de passe de l'utilisateur connecté.
 */
class GestionController extends AbstractController
{
    /**
     * Affiche la page de gestion personnelle.
     */
    #[Route('/gestion', name: 'app_gestion')]
    public function index(): Response
    {
        // Affiche la vue Twig associée à la page de gestion
        return $this->render('gestion/gestion.html.twig', [
            'controller_name' => 'GestionController',
        ]);
    }

    /**
     * Met à jour le mot de passe de l'utilisateur actuellement connecté.
     * Reçoit les données via une requête POST en JSON (AJAX).
     */
    #[Route('/update-password', name: 'update_password', methods: ['POST'])]
    public function updatePassword(
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $em
    ): JsonResponse {
        $user = $this->getUser();

        // Vérifie que l'utilisateur est bien connecté
        if (!$user) {
            return new JsonResponse(['status' => 'error', 'message' => 'Utilisateur non connecté'], 403);
        }

        $data = json_decode($request->getContent(), true);

        // Vérifie que le mot de passe est fourni et d'une longueur minimale
        if (!isset($data['password']) || strlen($data['password']) < 6) {
            return new JsonResponse(['status' => 'error', 'message' => 'Mot de passe invalide'], 400);
        }

        // Hashage du nouveau mot de passe
        $hashedPassword = $passwordHasher->hashPassword($user, $data['password']);
        $user->setPassword($hashedPassword);

        $em->flush();

        return new JsonResponse(['status' => 'success']);
    }
}
