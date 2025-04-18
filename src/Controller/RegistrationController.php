<?php
// src/Controller/RegistrationController.php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register', methods: ['GET', 'POST'])]
    public function register(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod('POST')) {
            $user = new User();

            $user->setEmail($request->request->get('email'));
            $user->setFirstName($request->request->get('first_name'));
            $user->setLastName($request->request->get('last_name'));
            $user->setPhoneNumber($request->request->get('phone_number'));
            $user->setBirthDate(new \DateTime($request->request->get('birth_date')));
            $user->setDateCreation(new \DateTime());

            $role = $request->request->get('role');
            $user->setRole($role); // Attention : vérifie que ce champ est bien string(5)

            $password = $passwordHasher->hashPassword($user, $request->request->get('password'));
            $user->setPassword($password);

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_login'); // ou app_home
        }

        return $this->render('registration/register.html.twig');
    }



}