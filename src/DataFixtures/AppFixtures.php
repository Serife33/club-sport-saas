<?php

namespace App\DataFixtures;

use App\Entity\Club;
use App\Entity\Team;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $hasher
    ) {}

    public function load(ObjectManager $manager): void
    {
        // Créer le club
        $club = new Club();
        $club->setName('FC Test');
        $club->setSport('Football');
        $club->setSlug('fc-test');
        $club->setContactEmail('contact@fc-test.fr');
        $manager->persist($club);

        // Créer l'admin
        $admin = new User();
        $admin->setEmail('admin@fc-test.fr');
        $admin->setFirstname('Admin');
        $admin->setLastname('Test');
        $admin->setRole('ADMIN');
        $admin->setStatus('ACTIVE');
        $admin->setBirthdate(new \DateTime('1990-01-01'));
        $admin->setClub($club);
        $admin->setPassword($this->hasher->hashPassword($admin, 'password'));
        $manager->persist($admin);

        // créer une team 
        $team = new Team();
        $team->setName('equipe');
        $team->setSeason('2026');
        $team->setStatus('active');
        $manager->persist($team);

        $manager->flush();
    }
}