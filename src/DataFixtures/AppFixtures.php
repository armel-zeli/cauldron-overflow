<?php

namespace App\DataFixtures;

use App\Entity\Tag;
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

        $question = QuestionFactory::createOne();

        $tag1 = new Tag();
        $tag1->setName('dinosaurs');
        $tag2 = new Tag();
        $tag2->setName('monster trucks');

        $question->addTag($tag1);
        $question->addTag($tag2);

        $manager->persist($tag1);
        $manager->persist($tag2);

        $manager->flush();
    }
}
