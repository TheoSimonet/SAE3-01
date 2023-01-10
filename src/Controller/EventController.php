<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\EventType;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EventController extends AbstractController
{
    #[Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_ETUDIANT') or is_granted('ROLE_ENSEIGNANT') or is_granted('ROLE_ENTREPRISE')")]
    #[Route('/event', name: 'app_event')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();
        $events = $em->getRepository(Event::class)->orderedByDate();

        return $this->render('event/index.html.twig', [
            'events' => $events,
        ]);
    }
    #[Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_ETUDIANT') or is_granted('ROLE_ENSEIGNANT')")]
    #[Route('/event/{id}', name: 'app_event_show', requirements: ['id' => '\d+'])]
    public function show(Event $event): Response
    {
        return $this->render('event/show.html.twig', ['event' => $event]);
    }

    #[Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_ENSEIGNANT')")]
    #[Route('/event/{id}/update', name: 'app_event_update', requirements: ['id' => '\d+'])]
    public function update(Event $event, Request $request, ManagerRegistry $doctrine): Response
    {
        if (!in_array('ROLE_ADMIN' or 'ROLE_ENSEIGNANT', $this->getUser()->getRoles())) {
            return $this->redirectToRoute('app_event');
        }

        $form = $this->createForm(EventType::class, $event);
        $form->add('save', SubmitType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $doctrine->getManager();

            if (!$event) {
                throw $this->createNotFoundException('No event found for id '.$event->getId());
            }

            $event->setTitle($form->getData()->getTitle());
            $event->setText($form->getData()->getText());
            $entityManager->flush();

            return $this->redirectToRoute('app_event');
        }

        return $this->render('event/update.html.twig', [
            'event' => $event,
            'form' => $form->createView(),
        ]);
    }

    #[Security("is_granted('ROLE_ADMIN') or  is_granted('ROLE_ENSEIGNANT')")]
    #[Route('/event/create', name: 'app_event_create')]
    public function create(Request $request, ManagerRegistry $doctrine): Response
    {
        $event = new Event();
        $form = $this->createForm(EventType::class, $event);
        $form->add('Ajouter', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($event);
            $entityManager->flush();

            return $this->redirectToRoute('app_event');
        }

        return $this->render('event/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_ENSEIGNANT')")]
    #[Route('/event/{id}/delete', name: 'app_event_delete', requirements: ['id' => '\d+'])]
    public function delete(Event $event, Request $request, ManagerRegistry $doctrine): Response
    {
        if (!in_array('ROLE_ADMIN' or 'ROLE_ENSEIGNANT', $this->getUser()->getRoles())) {
            return $this->redirectToRoute('app_event');
        }

        $form = $this->createFormBuilder()
            ->add('delete', SubmitType::class, [
                'attr' => ['class' => 'projet__form__delete'],
            ])
            ->add('cancel', SubmitType::class, [
                'attr' => ['class' => 'projet__form__cancel'],
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $doctrine->getManager();

            if ($form->getClickedButton() === $form->get('delete')) {
                $entityManager->remove($event);
                $entityManager->flush();

                return $this->redirectToRoute('app_event');
            }

            if ($form->getClickedButton() === $form->get('cancel')) {
                return $this->redirectToRoute('app_event', [
                    'id' => $event->getId(),
                ]);
            }
        }

        return $this->render('event/delete.html.twig', [
            'event' => $event,
            'form' => $form->createView(),
        ]);
    }
}
