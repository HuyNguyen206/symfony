<?php

namespace App\Controller;

use App\Entity\Question;
use App\Repository\QuestionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    public function homepage(QuestionRepository $questionRepository)
    {
//        $questionRepo = $entityManager->getRepository(Question::class);
//        $questions = $questionRepo->findBy([], ['askedAt' => 'desc']);
        $questions = $questionRepository->findAllAskedOrderedByNewest();
        return $this->render('homepage.html.twig', compact('questions'));
    }

    #[Route('questions/new', name: 'questions.create')]
    public function new(EntityManagerInterface $entityManager)
    {
        return new Response('Sounds like a GREAT feature for V2!');
    }

    #[Route('/questions/{slug}', name: 'questions.show')]
    public function show(Question $question)
    {
//        $questionRepo = $entityManager->getRepository(Question::class);
//        /**
//         * @var Question|null $question
//         */
//        $question = $questionRepo->findOneBy(['slug' => $slug]);
//        if ($question === null) {
//            throw $this->createNotFoundException(sprintf('No question found for slug %s', $slug));
//        }

        $answers = [
            'Make sure your cat is sitting `purrrfectly` still ?',
            'Honestly, I like furry shoes better than MY cat',
            'Maybe... try saying the spell backwards?',
        ];

        return $this->render('question/show.html.twig', compact('question', 'answers'));
    }
    #[Route('questions/{slug}/vote', name: 'questions.vote', methods: 'POST')]
    public function updateVote(Question $question, EntityManagerInterface $entityManager, Request $request)
    {
        $isIncrease = $request->get('direction') === 'up';
        if ($isIncrease) {
            $question->upVote();
        } else {
            $question->downVote();
        }

        $entityManager->flush();

        return $this->redirectToRoute('questions.show', ['slug' => $question->getSlug()]);
    }

}
