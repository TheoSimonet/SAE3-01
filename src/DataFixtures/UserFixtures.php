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
        UserFactory::createMany(10);
    }
}
