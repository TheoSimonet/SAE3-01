<?php

namespace App\Controller;

use App\Entity\Alternance;
use App\Form\AlternanceType;
use App\Repository\AlternanceRepository;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AlternanceController extends AbstractController
{
    #[Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_ETUDIANT') or is_granted('ROLE_ENSEIGNANT') or is_granted('ROLE_ENTREPRISE')")]
    #[Route('/alternance', name: 'app_alternance')]
    public function index(AlternanceRepository $alternanceRepository): Response
    {
        $alternance = $alternanceRepository->findBy([], ['id' => 'ASC', 'titre' => 'ASC', 'description' => 'ASC']);

        return $this->render('alternance/index.html.twig', [
            'alternances' => $alternance,
        ]);
    }

    #[Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_ETUDIANT') or is_granted('ROLE_ENSEIGNANT') or is_granted('ROLE_ENTREPRISE')")]
    #[Route('/alternance/{id}', name: 'app_alternance_show', requirements: ['id' => '\d+'])]
    public function show(Alternance $alternance): Response
    {
        return $this->render('alternance/show.html.twig', ['alternance' => $alternance]);
    }

    #[Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_ENTREPRISE')")]
    #[Route('/alternance/{id}/update', name: 'app_alternance_update', requirements: ['id' => '\d+'])]
    public function update(Alternance $alternance, Request $request, ManagerRegistry $doctrine): Response
    {
        if ($this->getUser()->getId() !== $alternance->getAuthor()->getId()) {
            throw $this->createAccessDeniedException('Vous ne pouvez pas modifier cette alternance');
        }

        $form = $this->createForm(AlternanceType::class, $alternance);
        $form->add('save', SubmitType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $doctrine->getManager();

            if (!$alternance) {
                throw $this->createNotFoundException('No project found for id '.$alternance->getId());
            }

            $alternance->setTitre($form->getData()->getTitre());
            $alternance->setDescription($form->getData()->getDescription());
            $entityManager->flush();

            return $this->redirectToRoute('app_alternance', [
                'id' => $alternance->getId(),
            ]);
        }

        return $this->render('alternance/update.html.twig', [
            'alternance' => $alternance,
            'form' => $form->createView(),
        ]);
    }

    #[Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_ENTREPRISE')")]
    #[Route('/alternance/create', name: 'app_alternance_create')]
    public function create(ManagerRegistry $doctrine, Request $request): Response
    {
        $alternance = new Alternance();

        $form = $this->createForm(AlternanceType::class, $alternance);
        $form->add('save', SubmitType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $alternance->setTitre($form->getData()->getTitre());
            $alternance->setDescription($form->getData()->getDescription());
            $alternance->setAuthor($this->getUser());

            $em = $doctrine->getManager();
            $em->persist($alternance);
            $em->flush();

            return $this->redirectToRoute('app_alternance', [
                'id' => $alternance->getId(),
            ]);
        }

        return $this->render('alternance/create.html.twig', [
            'alternance' => $alternance,
            'form' => $form->createView(),
        ]);
    }

    #[Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_ENTREPRISE')")]
    #[Route('/alternance/{id}/delete', name: 'app_alternance_delete', requirements: ['id' => '\d+'])]
    public function delete(Alternance $alternance, Request $request, ManagerRegistry $doctrine): Response
    {
        if ($this->getUser()->getId() !== $alternance->getAuthor()->getId()) {
            throw $this->createAccessDeniedException('Vous ne pouvez pas supprimer cette alternance');
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
                $entityManager->remove($alternance);
                $entityManager->flush();

                return $this->redirectToRoute('app_alternance');
            }

            if ($form->getClickedButton() === $form->get('cancel')) {
                return $this->redirectToRoute('app_alternance_show', [
                    'id' => $alternance->getId(),
                ]);
            }
        }

        return $this->render('alternance/delete.html.twig', [
            'alternance' => $alternance,
            'form' => $form->createView(),
        ]);
    }
}
