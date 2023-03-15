<?php

namespace App\Controller;

use App\Entity\ProjetTER;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Security;

class DeleteProjetTERController extends AbstractController
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function __invoke(ProjetTER $projet, ManagerRegistry $doctrine): Response
    {
        if (!$this->security->isGranted('ROLE_ENSEIGNANT') || $projet->getAuthor() !== $this->security->getUser()) {
            throw new AccessDeniedException();
        }

        $em = $doctrine->getManager();
        $em->remove($projet);
        $em->flush();

        return new Response(null, Response::HTTP_NO_CONTENT);
    }
}
