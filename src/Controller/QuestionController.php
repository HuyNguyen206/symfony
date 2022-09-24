<?php

namespace App\Controller;

use App\Entity\Question;
use App\Repository\QuestionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuestionController extends AbstractController {

    #[Route('/question', name: 'app_question')]
    public function index(): Response
    {
        return $this->render('question/index.html.twig', [
            'controller_name' => 'QuestionController',
        ]);
    }

    #[Route('/', name: 'homepage')]
    public function homepage(EntityManagerInterface $entityManager)
    {
        $questionRepo = $entityManager->getRepository(Question::class);
//        $questions = $questionRepo->findBy([], ['askedAt' => 'desc']);
        $questions = $questionRepo->findAllAskedOrderedByNewest();
        return $this->render('homepage.html.twig', compact('questions'));
    }

    #[Route('questions/new', name: 'questions.create')]
    public function new(EntityManagerInterface $entityManager)
    {
        $question = new Question();
        $question->setName('Missing pants');
        $question->setSlug('missing-pants-'.rand(0, 10000));
        $question->setQuestion(<<<EOF
Hi! So... I'm having a *weird* day. Yesterday, I cast a spell
to make my dishes wash themselves. But while I was casting it,
I slipped a little and I think `I also hit my pants with the spell`.
When I woke up this morning, I caught a quick glimpse of my pants
opening the front door and walking out! I've been out all afternoon
(with no pants mind you) searching for them.
Does anyone have a spell to call your pants back?
EOF
);
        if(rand(1, 10) > 2) {
            $question->setAskedAt(new \DateTime(sprintf('-%d days', rand(1, 100))));
        }
        $entityManager->persist($question);
        $entityManager->flush();

        return new Response(sprintf('Well hallo! The shiny new question is id #%d, slug: %s',
        $question->getId(),
        $question->getSlug()));
    }

    #[Route('/questions/{slug}', name: 'questions.show')]
    public function show($slug, EntityManagerInterface $entityManager)
    {
        $questionRepo = $entityManager->getRepository(Question::class);
        /**
         * @var Question|null $question
         */
        $question = $questionRepo->findOneBy(['slug' => $slug]);
        if ($question === null) {
            throw $this->createNotFoundException(sprintf('No question found for slug %s', $slug));
        }

        $answers = [
            'Make sure your cat is sitting `purrrfectly` still ?',
            'Honestly, I like furry shoes better than MY cat',
            'Maybe... try saying the spell backwards?',
        ];

        return $this->render('question/show.html.twig', compact('question', 'answers'));
    }

}
