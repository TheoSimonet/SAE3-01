<?php

namespace App\Controller;

use App\Repository\StageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StageController extends AbstractController
{
    #[Route('/stage', name: 'app_stage')]
    public function index(StageRepository $stageRepository): Response
    {
        $stage = $stageRepository->findBy([],['id'=>'ASC','titre'=>'ASC', 'description'=>'ASC']);

        return $this->render('stage/index.html.twig', [
            'stages' => $stage,
        ]);
    }
}
