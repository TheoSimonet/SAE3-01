<?php

namespace App\Controller;

use App\Entity\Selection;
use App\Repository\ProjetTERRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

class CreateSelectionController extends AbstractController
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function __invoke(Request $request, ManagerRegistry $doctrine, ProjetTERRepository $repository): Response
    {
        $selection = new Selection();
        $selection->setIdUser($this->security->getUser());

        if (!$this->security->isGranted('ROLE_ETUDIANT')) {
            throw new AccessDeniedException();
        }

        $data = json_decode($request->getContent(), true);

        $iriProjet = $data['idProjet'];
        $idProjet = $iriProjet[-1];

        $projet = $repository->find($idProjet);

        if (!$projet) {
            throw new NotFoundResourceException('Projet inexistant');
        }

        $selection->setIdProjet($projet);

        $date = new \DateTime();
        $selection->setDate($date);

        $em = $doctrine->getManager();
        $em->persist($selection);
        $em->flush();

        return new Response(json_encode($selection), Response::HTTP_CREATED, [
            'Content-Type' => 'application/json',
        ]);
    }
}
