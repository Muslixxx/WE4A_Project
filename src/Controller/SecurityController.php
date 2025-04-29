<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Contrôleur dédié à l'authentification des utilisateurs.
 * Gère l'affichage du formulaire de connexion et le point de déconnexion.
 */
class SecurityController extends AbstractController
{
    /**
     * Affiche le formulaire de connexion.
     * Utilise le service `AuthenticationUtils` pour récupérer les erreurs éventuelles
     * et préremplir le champ email/identifiant avec la dernière tentative.
     */
    #[Route('/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Récupère la dernière erreur de connexion (si présente)
        $error = $authenticationUtils->getLastAuthenticationError();

        // Récupère le dernier identifiant entré par l'utilisateur
        $lastUsername = $authenticationUtils->getLastUsername();

        // Rend le template login avec les variables nécessaires
        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    /**
     * Point d'entrée de la déconnexion.
     * Cette méthode n'est jamais exécutée : Symfony l'intercepte via le firewall.
     * Cependant il faut la définir pour activer le mécanisme de logout.
     */
    #[Route('/logout', name: 'app_logout')]
    public function logout(): void
    {
        // Cette exception est attendue par symfony
        throw new \LogicException('Cette méthode est interceptée par le firewall de Symfony.');
    }
}
