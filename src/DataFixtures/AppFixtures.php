<?php

namespace App\DataFixtures;

use App\Entity\Answer;
use App\Entity\Question;
use App\Factory\AnswerFactory;
use App\Factory\QuestionFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $questions = QuestionFactory::createMany(20);
        QuestionFactory::new()
            ->unpublished()
            ->createMany(5);

        QuestionFactory::new()
            ->createMany(5);

       AnswerFactory::createMany(100, function() use ($questions) {
        return [
            'question' => $questions[array_rand($questions)]
        ];
    });

        AnswerFactory::new()->needApproval()->many(5)->create(function() use ($questions) {
            return [
                'question' => $questions[array_rand($questions)]
            ];
        });

        $manager->flush();

//        return new Response(sprintf('Well hallo! The shiny new question is id #%d, slug: %s',
//            $question->getId(),
//            $question->getSlug()));

    }
}
