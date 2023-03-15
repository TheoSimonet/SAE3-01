<?php

namespace App\Controller;

use App\Entity\ProjetTER;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Security;

class UpdateProjetTERController extends AbstractController
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function __invoke(ProjetTER $projet, Request $request, ManagerRegistry $doctrine): Response
    {
        if (!$this->security->isGranted('ROLE_ENSEIGNANT') || $projet->getAuthor() !== $this->security->getUser()) {
            throw new AccessDeniedException();
        }

        $data = json_decode($request->getContent(), true);

        $projet->setTitre($data['titre']);
        $projet->setDescription($data['description']);
        $projet->setLibProjet($data['libProjet']);

        $date = new \DateTime();
        $projet->setDate($date);

        $em = $doctrine->getManager();
        $em->persist($projet);
        $em->flush();

        return new Response(json_encode($projet), Response::HTTP_OK, [
            'Content-Type' => 'application/json',
        ]);
    }
}
