<?php

namespace App\Controller;

use App\Entity\Faq;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Security;


class CreateFaqController extends AbstractController
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function __invoke(Request $request, ManagerRegistry $doctrine): Response
    {
        $faq = new Faq();

        if (!$this->security->isGranted('ROLE_ENSEIGNANT') && $this->security->isGranted('ROLE_ENSEIGNANT') ) {
            throw new AccessDeniedException();
        }

        $data = json_decode($request->getContent(), true);

        $faq->setQuestion($data['question']);
        $faq->setReponse($data['reponse']);

        $em = $doctrine->getManager();
        $em->persist($faq);
        $em->flush();

        return new Response(json_encode($faq), Response::HTTP_CREATED, [
            'Content-Type' => 'application/json',
        ]);
    }
}
