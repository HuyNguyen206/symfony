<?php

namespace App\Controller;

use App\Entity\Answer;
use App\Entity\Question;
use App\Repository\AnswerRepository;
use App\Repository\QuestionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
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

    #[Route('/{page<\d+>}', name: 'homepage')]
    public function homepage(QuestionRepository $questionRepository, Request $request, int $page = 1)
    {
//        $questionRepo = $entityManager->getRepository(Question::class);
//        $questions = $questionRepo->findBy([], ['askedAt' => 'desc']);
        $queryBuilder = $questionRepository->createAskedOrderedByNewestQueryBuilder();
        $pagerfanta = (new Pagerfanta(
            new QueryAdapter($queryBuilder)
        ))->setMaxPerPage(2)
        ->setCurrentPage($page);

        return $this->render('homepage.html.twig', compact('pagerfanta'));
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
//        $answers = $answerRepository->findBy(['question' => $question]);

        return $this->render('question/show.html.twig', compact('question'));
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

    #[Route('answers/{answer}/vote', name: 'answers.vote', methods: 'POST')]
    public function updateAnswerVote(Answer $answer, EntityManagerInterface $entityManager, Request $request)
    {
        $isIncrease = $request->get('direction') === 'up';
        if ($isIncrease) {
            $answer->upVote();
        } else {
            $answer->downVote();
        }

        $entityManager->flush();

        return $this->redirectToRoute('questions.show', ['slug' => $answer->getQuestion()->getSlug()]);
    }


}
