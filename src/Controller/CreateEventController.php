<?php

namespace App\Controller;

use App\Entity\Event;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Security;

class CreateEventController extends AbstractController
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function __invoke(Request $request, ManagerRegistry $doctrine): Response
    {
        $event = new Event();

        if (!$this->security->isGranted('ROLE_ENSEIGNANT')) {
            throw new AccessDeniedException();
        }

        $data = json_decode($request->getContent(), true);

        $event->setTitle($data['title']);
        $event->setText($data['text']);

        $em = $doctrine->getManager();
        $em->persist($event);
        $em->flush();

        return new Response(json_encode($event), Response::HTTP_CREATED, [
            'Content-Type' => 'application/json',
        ]);
    }
}
