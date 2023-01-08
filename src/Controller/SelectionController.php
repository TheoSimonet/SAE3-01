<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SelectionController extends AbstractController
{
    #[Route('/selection', name: 'app_selection')]
    public function index(): Response
    {
        return $this->render('selection/index.html.twig', [
            'controller_name' => 'SelectionController',
        ]);
    }
}
