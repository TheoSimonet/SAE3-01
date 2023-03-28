<?php

namespace App\Controller;

use App\Entity\ProjetTER;
use App\Entity\Selection;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Security;

class DeleteSelectionController extends AbstractController
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function __invoke(Selection $selection, ManagerRegistry $doctrine): Response
    {
        if (!$this->security->isGranted('ROLE_ETUDIANT') || $selection->getIdUser() !== $this->security->getUser()) {
            throw new AccessDeniedException();
        }

        $em = $doctrine->getManager();
        $em->remove($selection);
        $em->flush();

        return new Response(null, Response::HTTP_NO_CONTENT);
    }
}
