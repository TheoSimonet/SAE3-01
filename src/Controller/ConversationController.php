<?php

namespace App\Controller;

use App\Entity\Conversation;
use App\Entity\Message;
use App\Form\MessageType;
use DateTimeImmutable;
use DateTimeZone;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ConversationController extends AbstractController
{
    #[Route('/conversation', name: 'app_conversation')]
    public function index(): Response
    {
        return $this->render('conversation/index.html.twig', [
            'conversations' => $this->getUser()->getConversations(),
        ]);
    }

    #[Route('/conversation/{id}', name: 'app_conversation_show', requirements: ['id' => '\d+'])]
    public function show(Conversation $conversation, ManagerRegistry $doctrine, Request $request): Response
    {
        $message = new Message();
        $user = $this->getUser();

        $form = $this->createForm(MessageType::class, $message);
        $form->add('save', SubmitType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $message->setContent($form->getData()->getContent());
            $message->setConversation($conversation);
            $message->setSendAt(new DateTimeImmutable("now"));
            $message->setSenderId($user);

            $em = $doctrine->getManager();
            $em->persist($message);
            $em->flush();

            return $this->redirectToRoute('app_conversation_show', [
                'id' => $conversation->getId(),
            ]);

        }

        return $this->render('conversation/show.html.twig', [
            'form' => $form->createView(),
            'conversation' => $conversation
        ]);

    }
}
