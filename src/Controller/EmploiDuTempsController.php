<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_ENSEIGNANT') or is_granted('ROLE_ETUDIANT')")]
class EmploiDuTempsController extends AbstractController
{
    #[Route('/emploi_du_temps', name: 'app_emploi_du_temps')]
    public function index(): Response
    {
        return $this->render('emploi_du_temps/index.html.twig', [
            'controller_name' => 'EmploiDuTempsController',
        ]);
    }
}
