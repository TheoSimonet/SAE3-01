<?php

namespace App\DataFixtures;

use App\Factory\AlternanceFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AlternanceFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        AlternanceFactory::createMany(10);
    }
}
