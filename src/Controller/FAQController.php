<?php

namespace App\Controller;

use App\Entity\Faq;
use App\Form\FaqType;
use App\Repository\FaqRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FAQController extends AbstractController
{
    #[Route('/faq', name: 'app_faq')]
    public function index(FaqRepository $faqRepository): Response
    {

        $faq = $faqRepository->findBy([], ['id' => 'ASC']);

        return $this->render('faq/index.html.twig', [
            'faqs' => $faq,
        ]);
    }

    #[Route('/faq/{id}/update', name: 'app_faq_update', requirements: ['id' => '\d+'])]
    public function update(Faq $faq, Request $request, ManagerRegistry $doctrine): Response
    {
        $form = $this->createForm(FaqType::class, $faq);
        $form->add('save', SubmitType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $doctrine->getManager();

            if (!$faq) {
                throw $this->createNotFoundException('No project found for id ' . $faq->getId());
            }

            $faq->setQuestion($form->getData()->getQuestion());
            $faq->setReponse($form->getData()->getReponse());
            $entityManager->flush();

            return $this->redirectToRoute('app_faq', [
                'id' => $faq->getId(),
            ]);
        }

        return $this->render('faq/update.html.twig', [
            'faq' => $faq,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/faq/create', name: 'app_faq_create')]
    public function create(ManagerRegistry $doctrine, Request $request): Response
    {
        $faq = new Faq();

        $form = $this->createForm(FaqType::class, $faq);
        $form->add('save', SubmitType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $faq->setReponse($form->getData()->getReponse());
            $faq->setQuestion($form->getData()->getQuestion());

            $em = $doctrine->getManager();
            $em->persist($faq);
            $em->flush();

            return $this->redirectToRoute('app_faq', [
                'id' => $faq->getId(),
            ]);
        }

        return $this->render('faq/create.html.twig', [
            'faq' => $faq,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/faq/{id}/delete', name: 'app_faq_delete', requirements: ['id' => '\d+'])]
    public function delete(Faq $faq, Request $request, ManagerRegistry $doctrine): Response
    {
        $form = $this->createFormBuilder()
            ->add('cancel', SubmitType::class, [
                'attr' => ['class' => 'faq__form__cancel'],
            ])
            ->add('delete', SubmitType::class, [
                'attr' => ['class' => 'faq__form__delete'],
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $doctrine->getManager();

            if ($form->getClickedButton() === $form->get('delete')) {
                $entityManager->remove($faq);
                $entityManager->flush();

                return $this->redirectToRoute('app_faq');
            }

            if ($form->getClickedButton() === $form->get('cancel')) {
                return $this->redirectToRoute('app_faq', [
                    'id' => $faq->getId(),
                ]);
            }
        }

        return $this->render('faq/delete.html.twig', [
            'faq' => $faq,
            'form' => $form->createView(),
        ]);
    }
}
