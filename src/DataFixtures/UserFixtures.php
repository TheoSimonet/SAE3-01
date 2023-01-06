<?php

namespace App\DataFixtures;

use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        UserFactory::createOne(['email' => 'root@example.com', 'password' => 'admin', 'roles' => ['ROLE_ADMIN'], 'firstname' => 'admin', 'lastname' => 'admin']);
        UserFactory::createOne(['email' => 'cyril.manil08@gmail.com', 'password' => 'cyril', 'roles' => ['ROLE_ETUDIANT'], 'firstname' => 'Cyril', 'lastname' => 'Manil', 'phone' => '0782485067']);
        UserFactory::createOne(['email' => 'matheo.olsen@suntorydev.fr', 'password' => 'suntorydev', 'roles' => ['ROLE_ENTREPRISE'], 'firstname' => 'Mathéo', 'lastname' => 'Olsen']);
        UserFactory::createOne(['email' => 'philippe.vautrot@univ-reims.fr', 'password' => 'phil', 'roles' => ['ROLE_ENSEIGNANT'], 'firstname' => 'Philippe', 'lastname' => 'Vautrot']);
        UserFactory::createMany(10);
    }
}
