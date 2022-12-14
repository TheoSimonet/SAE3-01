<?php

namespace App\Controller;

use App\Repository\FaqRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FAQController extends AbstractController
{
    #[Route('/faq', name: 'app_f_a_q')]
    public function index(FaqRepository $faqRepository): Response
    {

        $faq = $faqRepository->findBy([], ['id' => 'ASC']);

        return $this->render('faq/index.html.twig', [
            'faqs' => $faq,
        ]);
    }
}
