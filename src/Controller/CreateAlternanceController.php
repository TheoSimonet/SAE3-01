<?php

namespace App\Controller;

use App\Entity\Stage;
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
        $stage = new Stage();
        $stage->setAuthor($this->security->getUser());

        if (!$this->security->isGranted('ROLE_ENTREPRISE')) {
            throw new AccessDeniedException();
        }

        $data = json_decode($request->getContent(), true);

        $stage->setTitre($data['titre']);
        $stage->setDescription($data['description']);

        $em = $doctrine->getManager();
        $em->persist($stage);
        $em->flush();

        return new Response(json_encode($stage), Response::HTTP_CREATED, [
            'Content-Type' => 'application/json',
        ]);
    }
}
