<?php

namespace App\Controller;

use App\Entity\Subscriber;
use App\Form\SubscriberFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SubscriberController extends AbstractController
{
    #[Route('/subscriber/create', name: 'subscriber.create')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $subscriber = new Subscriber();
        $form = $this->createForm(SubscriberFormType::class, $subscriber);
        $form->handleRequest($request);
        $isAgree = $form->get('agreeTerm')->getData();
        if ($form->isSubmitted() && $form->isValid() && $isAgree) {
//            $subscriberData = $form->getData();
            $entityManager->persist($subscriber);
            $entityManager->flush();

            return $this->redirectToRoute('subscriber.index');
        }

        return $this->renderForm('subscriber/create.html.twig', compact('form'));
    }

    #[Route('subscribers', name: 'subscriber.index')]
    public function index(EntityManagerInterface $entityManager)
    {
        $subscribers = $entityManager->getRepository(Subscriber::class)->findAll();

        return $this->render('subscriber/index.html.twig', compact('subscribers'));
    }
}
