<?php

namespace App\Controller;

use App\Entity\Alternance;
use App\Form\AlternanceType;
use App\Form\UserType;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class ProfilController extends AbstractController
{
    #[Route('/profil', name: 'app_profil')]
    public function index(): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        $user = $this->getUser();

        return $this->render('profil/index.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/profil/candidatures', name: 'app_profil_candidatures')]
    public function candidatureShow(): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        $user = $this->getUser();
        $candidatures = $user->getCandidatures();

        return $this->render('profil/candidature_show.html.twig', [
            'candidatures' => $candidatures,
            'user' => $user,
        ]);
    }

    #[Route('/profil/stages', name: 'app_profil_stages')]
    public function stageShow(): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        $user = $this->getUser();
        $stages = $user->getStages();

        return $this->render('profil/stage_show.html.twig', [
            'stages' => $stages,
            'user' => $user,
        ]);
    }

    #[Route('/profil/alternances', name: 'app_profil_alternances')]
    public function alternanceShow(): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        $user = $this->getUser();
        $alternances = $user->getAlternances();

        return $this->render('profil/alternance_show.html.twig', [
            'alternances' => $alternances,
            'user' => $user,
        ]);
    }

    #[Route('/profil/update', name: 'app_profil_update')]
    public function update(UserPasswordHasherInterface $userPasswordHasher, Request $request, ManagerRegistry $doctrine): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        $user = $this->getUser();
        $form = $this->createForm(UserType::class, $user);
        $form->add('save', SubmitType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $doctrine->getManager();

            $user->setFirstname($form->getData()->getFirstname());
            $user->setLastname($form->getData()->getLastname());
            $user->setEmail($form->getData()->getEmail());
            $user->setPhone($form->getData()->getPhone());
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $entityManager->flush();

            return $this->redirectToRoute('app_profil');
        }

        return $this->render('profil/update.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }
}
