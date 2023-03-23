<?php

namespace App\Controller;

use App\Entity\Faq;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Security;


class DeleteFaqController extends AbstractController
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function __invoke(Faq $faq, ManagerRegistry $doctrine): Response
    {
        if (!$this->security->isGranted('ROLE_ENSEIGNANT') || !$this->security->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException();
        }

        $em = $doctrine->getManager();
        $em->remove($faq);
        $em->flush();

        return new Response(null, Response::HTTP_NO_CONTENT);
    }
}
