<?php

namespace App\Controller;

use App\Entity\Stage;
use App\Form\StageType;
use App\Repository\StageRepository;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

// #[IsGranted('ROLE_ADMIN', null, "Vous n'avez pas les permissions nécessaires pour accéder aux stages")]
class StageController extends AbstractController
{
    #[Route('/stage', name: 'app_stage')]
    public function index(StageRepository $stageRepository): Response
    {
        $stage = $stageRepository->findBy([], ['id' => 'ASC', 'titre' => 'ASC', 'description' => 'ASC']);

        return $this->render('stage/index.html.twig', [
            'stages' => $stage,
        ]);
    }

    #[Route('/stage/{id}', name: 'app_stage_show', requirements: ['id' => '\d+'])]
    public function show(Stage $stage): Response
    {
        return $this->render('stage/show.html.twig', ['stage' => $stage]);
    }

    #[Route('/stage/{id}/candidate', name: 'app_stage_candidate', requirements: ['id' => '\d+'])]
    public function candidate(Stage $stage): Response
    {
        return $this->redirectToRoute('app_candidature_new', [
            'idStage' => $stage->getId(),
        ]);
    }

    #[Route('/stage/{id}/update', name: 'app_stage_update', requirements: ['id' => '\d+'])]
    public function update(Stage $stage, Request $request, ManagerRegistry $doctrine): Response
    {
        $form = $this->createForm(StageType::class, $stage);
        $form->add('save', SubmitType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $doctrine->getManager();

            if (!$stage) {
                throw $this->createNotFoundException('No stage found for id ' . $stage->getId());
            }

            $stage->setNumStage($form->getData()->getNumStage());
            $stage->setTitre($form->getData()->getTitre());
            $stage->setDescription($form->getData()->getDescription());
            $entityManager->flush();

            return $this->redirectToRoute('app_stage', [
                'id' => $stage->getId(),
            ]);
        }

        return $this->render('stage/update.html.twig', [
            'stage' => $stage,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/stage/create', name: 'app_stage_create')]
    public function create(ManagerRegistry $doctrine, Request $request): Response
    {
        $stage = new Stage();
        $creator = $this->getUser()->getFirstname() . $this->getUser()->getLastname();

        $form = $this->createForm(StageType::class, $stage);
        $form->add('save', SubmitType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $stage->setNumStage($form->getData()->getNumStage());
            $stage->setTitre($form->getData()->getTitre() . ', publié par ' . $creator);
            $stage->setDescription($form->getData()->getDescription());

            $em = $doctrine->getManager();
            $em->persist($stage);
            $em->flush();

            return $this->redirectToRoute('app_stage', [
                'id' => $stage->getId(),
            ]);
        }

        return $this->render('stage/create.html.twig', [
            'stage' => $stage,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/stage/{id}/delete', name: 'app_stage_delete', requirements: ['id' => '\d+'])]
    public function delete(Stage $stage, Request $request, ManagerRegistry $doctrine): Response
    {
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
                $entityManager->remove($stage);
                $entityManager->flush();

                return $this->redirectToRoute('app_stage');
            }

            if ($form->getClickedButton() === $form->get('cancel')) {
                return $this->redirectToRoute('app_stage_show', [
                    'id' => $stage->getId(),
                ]);
            }
        }

        return $this->render('stage/delete.html.twig', [
            'stage' => $stage,
            'form' => $form->createView(),
        ]);
    }
}
