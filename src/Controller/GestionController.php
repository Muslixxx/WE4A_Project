<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class GestionController extends AbstractController
{
    #[Route('/gestion', name: 'app_gestion')]
    public function index(): Response
    {
        return $this->render('gestion/gestion.html.twig', [
            'controller_name' => 'GestionController',
        ]);
    }
    #[Route('/update-password', name: 'update_password', methods: ['POST'])]
    public function updatePassword(
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $em
    ): JsonResponse {
        $user = $this->getUser();

        if (!$user) {
            return new JsonResponse(['status' => 'error', 'message' => 'Utilisateur non connecté'], 403);
        }

        $data = json_decode($request->getContent(), true);

        if (!isset($data['password']) || strlen($data['password']) < 6) {
            return new JsonResponse(['status' => 'error', 'message' => 'Mot de passe invalide'], 400);
        }

        $hashedPassword = $passwordHasher->hashPassword($user, $data['password']);
        $user->setPassword($hashedPassword);

        $em->flush();

        return new JsonResponse(['status' => 'success']);
    }


}