<?php

namespace App\Controller;

use App\Repository\AlternanceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AlternanceController extends AbstractController
{
    #[Route('/alternance', name: 'app_alternance')]
    public function index(AlternanceRepository $alternanceRepository): Response
    {
        $alternance = $alternanceRepository->findBy([],['id'=>'ASC','titre'=>'ASC','description'=>'ASC']);

        return $this->render('alternance/index.html.twig', [
            'alternances' => $alternance,
        ]);
    }
}
