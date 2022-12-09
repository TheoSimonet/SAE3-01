<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProjetTERController extends AbstractController
{
    #[Route('/projet_ter', name: 'app_projet_ter')]
    public function index(): Response
    {
        return $this->render('projet_ter/index.html.twig', [
            'controller_name' => 'ProjetTERController',
        ]);
    }
}
