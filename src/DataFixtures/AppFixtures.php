<?php

namespace App\DataFixtures;

use App\Factory\BookFactory;
use App\Factory\ReaderFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        BookFactory::createMany(10);
        ReaderFactory::createMany(10);
    }
}
