<?php

namespace App\Controller;

use App\Repository\AnswerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AnswerController extends AbstractController
{
    #[Route('/answers/popular', name: 'answers.popular')]
    public function popularAnswer(AnswerRepository $answerRepository, Request $request): Response
    {
        $answers = $answerRepository->findMostPoplular($request->get('q'));
        return $this->render('answer/popular-answer.html.twig', compact('answers'));
    }
}
