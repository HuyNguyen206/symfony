<?php

namespace App\DataFixtures;

use App\Entity\Question;
use App\Factory\QuestionFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $entityManager): void
    {
        QuestionFactory::createMany(15);
        QuestionFactory::new()
            ->unpublished()
            ->createMany(5);

        $entityManager->flush();

//        return new Response(sprintf('Well hallo! The shiny new question is id #%d, slug: %s',
//            $question->getId(),
//            $question->getSlug()));

    }
}
