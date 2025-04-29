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
        $admin = new User();
        $admin->setEmail('admin@admin.com');
        $admin->setFirstName('Admin');
        $admin->setLastName('Principal');
        $admin->setPhoneNumber('0600000000');
        $admin->setBirthDate(new \DateTime('1990-01-01'));
        $admin->setRole('ROLE_ADMIN');
        $admin->setDateCreation(new \DateTime());

        $hashedPassword = $this->passwordHasher->hashPassword($admin, 'admin1234');
        $admin->setPassword($hashedPassword);

        $manager->persist($admin);
        $manager->flush();
    }
}
