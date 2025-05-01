<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixture extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // Création de l'administrateur
        $admin = new User();
        $admin->setEmail('admin@admin.com');
        $admin->setFirstName('Admin');
        $admin->setLastName('Principal');
        $admin->setPhoneNumber('0600000000');
        $admin->setBirthDate(new \DateTime('1990-01-01'));
        $admin->setRole('ROLE_ADMIN');
        $admin->setDateCreation(new \DateTime());
        $admin->setPassword(
            $this->passwordHasher->hashPassword($admin, 'aaaaaa')
        );
        $manager->persist($admin);

        // Création du professeur
        $prof = new User();
        $prof->setEmail('professeur@school.com');
        $prof->setFirstName('Paul');
        $prof->setLastName('Durand');
        $prof->setPhoneNumber('0611111111');
        $prof->setBirthDate(new \DateTime('1980-05-15'));
        $prof->setRole('ROLE_PROF');
        $prof->setDateCreation(new \DateTime());
        $prof->setPassword(
            $this->passwordHasher->hashPassword($prof, 'aaaaaa')
        );
        $manager->persist($prof);

        // Création de l'élève
        $eleve = new User();
        $eleve->setEmail('eleve@school.com');
        $eleve->setFirstName('Claire');
        $eleve->setLastName('Martin');
        $eleve->setPhoneNumber('0622222222');
        $eleve->setBirthDate(new \DateTime('2005-09-10'));
        $eleve->setRole('ROLE_ELEVE');
        $eleve->setDateCreation(new \DateTime());
        $eleve->setPassword(
            $this->passwordHasher->hashPassword($eleve, 'aaaaaa')
        );
        $manager->persist($eleve);

        // Envoi en base
        $manager->flush();
    }
}
