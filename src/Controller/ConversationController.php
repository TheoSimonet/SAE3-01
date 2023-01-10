<?php

namespace App\Controller;

use App\Entity\Conversation;
use App\Entity\Message;
use App\Form\ConversationType;
use App\Form\MessageType;
use App\Repository\ConversationRepository;
use DateTimeImmutable;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[IsGranted('ROLE_USER')]
class ConversationController extends AbstractController
{
    #[Route('/conversation', name: 'app_conversation')]
    public function index(ConversationRepository $conversationRepository): Response
    {
        $user = $this->getUser();
        $result = array();

        foreach ($conversationRepository->findAll() as $conversation) {
            if ($conversation->getAuthor() === $user || $conversation->getParticipant()->contains($user)) {
                $result[] = $conversation;
            }
        }

        return $this->render('conversation/index.html.twig', [
            'conversations' => $result,
        ]);
    }

    #[Route('/conversation/create', name: 'app_conversation_create')]
    public function create(ManagerRegistry $doctrine, Request $request, SluggerInterface $slugger)
    {
        $conversation = new Conversation();
        $user = $this->getUser();

        $form = $this->createForm(ConversationType::class, $conversation);
        $form->add('save', SubmitType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $conversation->setAuthor($user);
            $conversation->setCreatedAt(new DateTimeImmutable('now'));
            $conversation->setLocked(false);
            $conversation->setSubject($form->getData()->getSubject());

            $em = $doctrine->getManager();
            $em->persist($conversation);
            $em->flush();

            return $this->redirectToRoute('app_conversation_show', [
                'id' => $conversation->getId(),
            ]);
        }

        return $this->render('conversation/create.html.twig', [
            'conversation' => $conversation,
            'form' => $form->createView()
        ]);
    }

    #[Route('/conversation/{id}', name: 'app_conversation_show', requirements: ['id' => '\d+'])]
    public function show(Conversation $conversation, ManagerRegistry $doctrine, Request $request): Response
    {
        $user = $this->getUser();

        if ($conversation->getAuthor() === $user || $conversation->getParticipant()->contains($user)) {

            $message = new Message();

            $form = $this->createForm(MessageType::class, $message);
            $form->add('save', SubmitType::class);

            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $message->setContent($form->getData()->getContent());
                $message->setConversation($conversation);
                $message->setSendAt(new DateTimeImmutable('now'));
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
                'conversation' => $conversation,
            ]);

        }

        return $this->redirectToRoute('app_conversation');

    }

    #[Route('/conversation/{id}/close', name: 'app_conversation_close', requirements: ['id' => '\d+'])]
    public function close(Conversation $conversation, Request $request, ManagerRegistry $doctrine): Response
    {

        if ($this->getUser() !== $conversation->getAuthor()) {
            return $this->redirectToRoute('app_conversation_show', ['id' => $conversation->getId()]);
        }

        $conversation->setLocked(true);
        $doctrine->getManager()->flush();

        return $this->redirectToRoute('app_conversation_show', [
            'id' => $conversation->getId(),
        ]);
    }

    #[Route('/conversation/{id}/update', name: 'app_conversation_update', requirements: ['id' => '\d+'])]
    public function update(Conversation $conversation, Request $request, ManagerRegistry $doctrine): Response
    {

        $user = $this->getUser();

        if ($conversation->getAuthor() === $user) {

            $form = $this->createForm(ConversationType::class, $conversation);
            $form->add('save', SubmitType::class);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $entityManager = $doctrine->getManager();

                $conversation->setSubject($form->getData()->getSubject());
                $conversation->setParticipant($form->getData()->getParticipant());

                $entityManager->flush();

                return $this->redirectToRoute('app_conversation_show', [
                    'id' => $conversation->getId(),
                ]);

            }

            return $this->render('conversation/update.html.twig', [
                'conversation' => $conversation,
                'form' => $form->createView(),
            ]);

        } else if ($conversation->getParticipant()->contains($user)) {

            return $this->redirectToRoute('app_conversation_show', ['id' => $conversation->getId()]);

        }

        return $this->redirectToRoute('app_conversation');

    }
}
