<?php

namespace App\DataFixtures;

use App\Factory\FaqFactory;
use App\Repository\FaqRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class FaqFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        FaqFactory::createMany(20);
    }
}
