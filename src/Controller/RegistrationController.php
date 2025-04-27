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

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register', methods: ['GET', 'POST'])]
    public function register(
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $entityManager,
        UserRepository $userRepository
    ): Response {
        if ($request->isMethod('POST')) {
            $email = $request->request->get('email');
            $selectedRole = $request->request->get('role');

            // Autoriser uniquement ROLE_ELEVE ou ROLE_PROF
            if (!in_array($selectedRole, ['ROLE_ELEVE', 'ROLE_PROF'])) {
                $this->addFlash('error', 'Seuls les rôles Élève ou Professeur peuvent être sélectionnés.');
                return $this->redirectToRoute('app_register');
            }

            $existingUser = $userRepository->findOneBy(['email' => $email]);

            if ($existingUser) {
                $currentRole = $existingUser->getRole();

                if ($currentRole === 'ROLE_ELEVE') {
                    $this->addFlash('error', 'Un compte élève avec cet email existe déjà.');
                    return $this->redirectToRoute('app_register');
                }

                if (in_array($currentRole, ['ROLE_ADMIN', 'ROLE_PROF'])) {
                    $existingUser->setRole('ROLE_PROF_ADMIN');
                    $entityManager->flush();

                    $this->addFlash('success', 'Compte existant mis à jour en PROF_ADMIN.');
                    return $this->redirectToRoute('app_login');
                }

                $this->addFlash('error', 'Impossible de créer ou mettre à jour ce compte.');
                return $this->redirectToRoute('app_register');
            }

            // Nouveau compte
            $user = new User();
            $user->setEmail($email);
            $user->setFirstName($request->request->get('first_name'));
            $user->setLastName($request->request->get('last_name'));
            $user->setPhoneNumber($request->request->get('phone_number'));
            $user->setBirthDate(new \DateTime($request->request->get('birth_date')));
            $user->setDateCreation(new \DateTime());
            $user->setRole($selectedRole);

            $hashedPassword = $passwordHasher->hashPassword($user, $request->request->get('password'));
            $user->setPassword($hashedPassword);

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Votre compte a été créé avec succès.');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig');
    }
}
