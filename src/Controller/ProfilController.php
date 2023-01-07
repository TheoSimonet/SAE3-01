<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
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
}
