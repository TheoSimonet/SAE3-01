<?php

namespace App\Controller;

use App\Entity\Alternance;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Security;

class CreateAlternanceController extends AbstractController
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function __invoke(Request $request, ManagerRegistry $doctrine): Response
    {
        $alternance = new Alternance();
        $alternance->setAuthor($this->security->getUser());

        if (!$this->security->isGranted('ROLE_ENTREPRISE')) {
            throw new AccessDeniedException();
        }

        $data = json_decode($request->getContent(), true);

        $alternance->setTitre($data['titre']);
        $alternance->setDescription($data['description']);

        $em = $doctrine->getManager();
        $em->persist($alternance);
        $em->flush();

        return new Response(json_encode($alternance), Response::HTTP_CREATED, [
            'Content-Type' => 'application/json',
        ]);
    }
}
