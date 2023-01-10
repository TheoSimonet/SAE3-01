<?php

namespace App\DataFixtures;

use App\Factory\ProjetTERFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProjetTERFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        ProjetTERFactory::createMany(10);
    }
}
