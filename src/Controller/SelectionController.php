<?php

namespace App\Controller;

use App\Entity\ProjetTER;
use App\Entity\Selection;
use App\Form\SelectionType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SelectionController extends AbstractController
{
    #[Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_ETUDIANT')")]
    #[Route('/selection/create', name: 'app_selection_create')]
    public function create(ManagerRegistry $doctrine, Request $request): Response
    {
        $em = $doctrine->getManager();
        $projet = $em->getRepository(ProjetTER::class)->find($request->query->get('idProjet'));

        $selection = new Selection();
        $form = $this->createForm(SelectionType::class, $selection);
        $form->add('save', SubmitType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $selection->setIdUser($this->getUser());
            $selection->setDate(new \DateTimeImmutable('now'));

            $em->persist($selection);
            $em->flush();

            return $this->redirectToRoute('app_projet_ter', [
                'id' => $projet->getId(),
            ]);
        }

        return $this->render('seletion/create.html.twig', [
            'projet' => $projet,
            'form' => $form->createView(),
        ]);
    }
}
