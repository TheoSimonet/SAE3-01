<?php

namespace App\Controller;

use App\Entity\Candidature;
use App\Entity\Stage;
use App\Form\CandidatureType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class CandidatureController extends AbstractController
{
    #[Route('/candidature/new', name: 'app_candidature_new')]
    public function new(ManagerRegistry $doctrine, Request $request, SluggerInterface $slugger)
    {
        $candidature = new Candidature();
        $form = $this->createForm(CandidatureType::class, $candidature);
        $form->add('Valider', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $cvFile */
            $cvFile = $form->get('cvFilename')->getData();

            if ($cvFile) {
                $originalFilename = pathinfo($cvFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$cvFile->guessExtension();

                try {
                    $cvFile->move(
                        $this->getParameter('cvs_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                $candidature->setCvFilename($newFilename);
            }

            if ($form->isSubmitted() && $form->isValid()) {
                $candidature->setIdUser($this->getUser());

                $em = $doctrine->getManager();
                $idStage = $request->query->get('idStage');
                $candidature->setIdStage($em->getRepository(Stage::class)->find($idStage));
                $em->persist($candidature);
                $em->flush();
            }

            return $this->redirectToRoute('app_accueil');
        }

        return $this->renderForm('candidature/new.html.twig', [
            'form' => $form,
        ]);
    }
}
