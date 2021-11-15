<?php

namespace App\DataFixtures;

use App\Factory\AnswerFactory;
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
        $questions = QuestionFactory::new()->createMany(10);

        QuestionFactory::new()->unpublished()->createMany(5);

        AnswerFactory::new()->createMany(100, function () use ($questions) {
            return [
                'question' => $questions[array_rand($questions)]
            ];
        });

        AnswerFactory::new(function () use ($questions) {
            return [
                'question' => $questions[array_rand($questions)]
            ];
        })->needApproval()->many(20)->create();

        $manager->flush();
    }
}
