<?php

namespace App\DataFixtures;

use App\Factory\QuestionFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    /**
     * @param ObjectManager $manager
     * @throws \TypeError
     */
    public function load(ObjectManager $manager)
    {
        QuestionFactory::new()->createMany(5);
        QuestionFactory::new()->unpublished()->createMany(5);
        $manager->flush();
    }
}
