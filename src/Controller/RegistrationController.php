<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UserRepository;

/**
 * Contrôleur responsable de l'enregistrement des utilisateurs.
 */
class RegistrationController extends AbstractController
{
    /**
     * Route d’enregistrement des utilisateurs (GET + POST).
     * Gère à la fois l’affichage du formulaire et le traitement des données.
     */
    #[Route('/register', name: 'app_register', methods: ['GET', 'POST'])]
    public function register(
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $entityManager,
        UserRepository $userRepository
    ): Response {
        // Si la méthode est POST, on traite les données du formulaire
        if ($request->isMethod('POST')) {
            $email = $request->request->get('email');
            $selectedRole = $request->request->get('role');

            // On n'autorise que les rôles élève ou professeur
            if (!in_array($selectedRole, ['ROLE_ELEVE', 'ROLE_PROF'])) {
                $this->addFlash('error', 'Seuls les rôles Élève ou Professeur peuvent être sélectionnés.');
                return $this->redirectToRoute('app_register');
            }

            // Vérifie si un utilisateur existe déjà avec cet email
            $existingUser = $userRepository->findOneBy(['email' => $email]);

            if ($existingUser) {
                $currentRole = $existingUser->getRole();

                // Cas 1 : un compte élève existe déjà avec cet email
                if ($currentRole === 'ROLE_ELEVE') {
                    $this->addFlash('error', 'Un compte élève avec cet email existe déjà.');
                    return $this->redirectToRoute('app_register');
                }

                // Cas 2 : un admin ou prof existe → on élève le rôle à PROF_ADMIN
                if (in_array($currentRole, ['ROLE_ADMIN', 'ROLE_PROF'])) {
                    $existingUser->setRole('ROLE_PROF_ADMIN');
                    $entityManager->flush();

                    $this->addFlash('success', 'Compte existant mis à jour en PROF_ADMIN.');
                    return $this->redirectToRoute('app_login');
                }

                // Cas d'erreur : rôle inconnu ou non géré
                $this->addFlash('error', 'Impossible de créer ou mettre à jour ce compte.');
                return $this->redirectToRoute('app_register');
            }

            // Aucun compte existant : on crée un nouvel utilisateur
            $user = new User();
            $user->setEmail($email);
            $user->setFirstName($request->request->get('first_name'));
            $user->setLastName($request->request->get('last_name'));
            $user->setPhoneNumber($request->request->get('phone_number'));
            $user->setBirthDate(new \DateTime($request->request->get('birth_date')));
            $user->setDateCreation(new \DateTime());
            $user->setRole($selectedRole);

            // Hashage du mot de passe utilisateur
            $hashedPassword = $passwordHasher->hashPassword($user, $request->request->get('password'));
            $user->setPassword($hashedPassword);

            // Persistance en base
            $entityManager->persist($user);
            $entityManager->flush();

            // Message de confirmation et redirection vers la page de connexion
            $this->addFlash('success', 'Votre compte a été créé avec succès.');
            return $this->redirectToRoute('app_login');
        }

        // Si la méthode est GET : on affiche simplement le formulaire
        return $this->render('registration/register.html.twig');
    }
}
