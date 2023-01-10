<?php

namespace App\DataFixtures;

use App\Factory\StageFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class StageFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        StageFactory::createMany(10);
    }
}
