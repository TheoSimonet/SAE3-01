<?php

namespace App\Controller;

use App\Entity\ProjetTER;
use App\Entity\Selection;
use App\Form\SelectionType;
use App\Repository\SelectionRepository;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
            return $this->redirectToRoute('app_projet_ter');
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

    #[Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_ETUDIANT')")]
    #[Route('/selection/{id}/delete', name: 'app_selection_delete', requirements: ['id' => '\d+'])]
    public function delete(Selection $selection, Request $request, ManagerRegistry $doctrine): Response
    {
        if ($this->getUser()->getId() !== $selection->getIdUser()->getId()) {
            return $this->redirectToRoute('app_profil_selections');
        }

        $form = $this->createFormBuilder()
            ->add('delete', SubmitType::class, [
                'attr' => ['class' => 'projet__form__delete'],
            ])
            ->add('cancel', SubmitType::class, [
                'attr' => ['class' => 'projet__form__cancel'],
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $doctrine->getManager();

            if ($form->getClickedButton() === $form->get('delete')) {
                $entityManager->remove($selection);
                $entityManager->flush();

                return $this->redirectToRoute('app_profil_selections');
            }

            if ($form->getClickedButton() === $form->get('cancel')) {
                return $this->redirectToRoute('app_profil_selections');
            }
        }

        return $this->render('selection/delete.html.twig', [
            'selection' => $selection,
            'form' => $form->createView(),
        ]);
    }
}
