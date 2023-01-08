<?php

namespace App\Controller;

use App\Entity\ProjetTER;
use App\Entity\Selection;
use App\Form\SelectionType;
use App\Repository\SelectionRepository;
use Cassandra\Exception\AlreadyExistsException;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Exception\AlreadySubmittedException;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SelectionController extends AbstractController
{
    #[Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_ETUDIANT')")]
    #[Route('/selection/create', name: 'app_selection_create')]
    public function create(ManagerRegistry $doctrine, Request $request, SelectionRepository $selections): Response
    {
        $em = $doctrine->getManager();
        $projet = $em->getRepository(ProjetTER::class)->find($request->query->get('idProjet'));

        $selection = new Selection();
        $form = $this->createForm(SelectionType::class, $selection);
        $form->add('save', SubmitType::class);

        $areEqual = $selections->findEqual($this->getUser()->getId(), $projet->getId());
        if (0 != count($areEqual)) {
            throw new AlreadySubmittedException('Vous avez déjà sélectionné ce projet');
        }

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $selection->setIdUser($this->getUser());
            $selection->setIdProjet($projet);
            $selection->setDate(new \DateTimeImmutable('now'));

            $em->persist($selection);
            $em->flush();

            return $this->redirectToRoute('app_projet_ter', [
                'id' => $projet->getId(),
            ]);
        }

        return $this->render('selection/create.html.twig', [
            'projet' => $projet,
            'form' => $form->createView(),
        ]);
    }
}
