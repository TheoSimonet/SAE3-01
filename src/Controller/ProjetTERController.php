<?php

namespace App\Controller;

use App\Entity\ProjetTER;
use App\Form\ProjetTERType;
use App\Repository\ProjetTERRepository;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProjetTERController extends AbstractController
{
    #[Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_ETUDIANT') or is_granted('ROLE_ENSEIGNANT')")]
    #[Route('/projet_ter', name: 'app_projet_ter')]
    public function index(ProjetTERRepository $projetTERRepository): Response
    {
        $projetTER = $projetTERRepository->findBy([], ['id' => 'ASC', 'titre' => 'ASC', 'description' => 'ASC']);

        return $this->render('projet_ter/index.html.twig', [
            'projetsTER' => $projetTER,
        ]);
    }

    #[Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_ETUDIANT') or is_granted('ROLE_ENSEIGNANT')")]
    #[Route('/projet_ter/{id}', name: 'app_projet_ter_show', requirements: ['id' => '\d+'])]
    public function show(ProjetTER $projet): Response
    {
        return $this->render('projet_ter/show.html.twig', ['projet' => $projet]);
    }

    #[Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_ENSEIGNANT')")]
    #[Route('/projet_ter/{id}/update', name: 'app_projet_ter_update', requirements: ['id' => '\d+'])]
    public function update(ProjetTER $projet, Request $request, ManagerRegistry $doctrine): Response
    {
        if ($this->getUser()->getId() !== $projet->getAuthor()->getId() and !in_array("ROLE_ADMIN", $this->getUser()->getRoles())) {
            return $this->redirectToRoute('app_projet_ter');
        }

        $form = $this->createForm(ProjetTERType::class, $projet);
        $form->add('save', SubmitType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $doctrine->getManager();

            if (!$projet) {
                throw $this->createNotFoundException('No project found for id '.$projet->getId());
            }

            $projet->setTitre($form->getData()->getTitre());
            $projet->setDescription($form->getData()->getDescription());
            $projet->setAuthor($this->getUser());
            $projet->setDate(new \DateTimeImmutable('now'));
            $projet->setLibProjet($form->getData()->getLibProjet());
            $entityManager->flush();

            return $this->redirectToRoute('app_projet_ter', [
                'id' => $projet->getId(),
            ]);
        }

        return $this->render('projet_ter/update.html.twig', [
            'projet' => $projet,
            'form' => $form->createView(),
        ]);
    }

    #[Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_ENSEIGNANT')")]
    #[Route('/projet_ter/create', name: 'app_projet_ter_create')]
    public function create(ManagerRegistry $doctrine, Request $request): Response
    {
        $projet = new ProjetTER();
        $form = $this->createForm(ProjetTERType::class, $projet);
        $form->add('save', SubmitType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $projet->setTitre($form->getData()->getTitre());
            $projet->setDescription($form->getData()->getDescription());
            $projet->setAuthor($this->getUser());
            $projet->setDate(new \DateTimeImmutable('now'));
            $projet->setLibProjet($form->getData()->getLibProjet());

            $em = $doctrine->getManager();
            $em->persist($projet);
            $em->flush();

            return $this->redirectToRoute('app_projet_ter', [
                'id' => $projet->getId(),
            ]);
        }

        return $this->render('projet_ter/create.html.twig', [
            'projet' => $projet,
            'form' => $form->createView(),
        ]);
    }

    #[Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_ENSEIGNANT')")]
    #[Route('/projet_ter/{id}/delete', name: 'app_projet_ter_delete', requirements: ['id' => '\d+'])]
    public function delete(ProjetTER $projet, Request $request, ManagerRegistry $doctrine): Response
    {
        if ($this->getUser()->getId() !== $projet->getAuthor()->getId() and !in_array("ROLE_ADMIN", $this->getUser()->getRoles())) {
            return $this->redirectToRoute('app_projet_ter');
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
                $entityManager->remove($projet);
                $entityManager->flush();

                return $this->redirectToRoute('app_projet_ter');
            }

            if ($form->getClickedButton() === $form->get('cancel')) {
                return $this->redirectToRoute('app_projet_ter_show', [
                    'id' => $projet->getId(),
                ]);
            }
        }

        return $this->render('projet_ter/delete.html.twig', [
            'projet' => $projet,
            'form' => $form->createView(),
        ]);
    }
}
