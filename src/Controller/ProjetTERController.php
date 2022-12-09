<?php

namespace App\Controller;

use App\Entity\ProjetTER;
use App\Form\ProjetTERType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

class ProjetTERController extends AbstractController
{
    #[Route('/projet_ter', name: 'app_projet_ter')]
    public function index(): Response
    {
        return $this->render('projet_ter/index.html.twig', [
            'controller_name' => 'ProjetTERController',
        ]);
    }

    #[Route('/projet_ter/{id}/update', name: 'app_projet_ter_update', requirements: ['id' => '\d+'])]
    public function update(ProjetTER $projet, Request $request, ManagerRegistry $doctrine): Response
    {
        $form = $this->createForm(ProjetTERType::class, $projet);

        return $this->render('projet_ter/update.html.twig', [
            'projet' => $projet,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/projet_ter/create', name: 'app_projet_ter_create')]
    public function create(ManagerRegistry $doctrine, Request $request): Response
    {

    }

    #[Route('/projet_ter/{id}/delete', name: 'app_projet_ter_delete', requirements: ['id' => '\d+'])]
    public function delete(ProjetTER $projet, Request $request, ManagerRegistry $doctrine): Response
    {

    }
}
