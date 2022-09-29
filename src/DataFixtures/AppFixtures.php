<?php

namespace App\DataFixtures;

use App\Entity\Answer;
use App\Entity\Question;
use App\Entity\Tag;
use App\Factory\AnswerFactory;
use App\Factory\QuestionFactory;
use App\Factory\TagFactory;
use App\Factory\UserFactory;
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

        TagFactory::createMany(100);
        $questions = QuestionFactory::createMany(20, function () {
          return ['tags' => TagFactory::randomRange(0, 5)];
        });

        UserFactory::new(['email' => 'admin@admin.com']);
        UserFactory::createMany(10);
//        return new Response(sprintf('Well hallo! The shiny new question is id #%d, slug: %s',
//            $question->getId(),
//            $question->getSlug()));

    }
}
