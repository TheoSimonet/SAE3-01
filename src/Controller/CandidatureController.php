<?php

namespace App\Controller;

use App\Entity\Candidature;
use App\Entity\Stage;
use App\Form\CandidatureType;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class CandidatureController extends AbstractController
{
    #[Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_ETUDIANT')")]
    #[Route('/candidature/new', name: 'app_candidature_new')]
    public function new(ManagerRegistry $doctrine, Request $request, SluggerInterface $slugger)
    {
        $em = $doctrine->getManager();
        $stage = $em->getRepository(Stage::class)->find($request->query->get('idStage'));

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
                $candidature->setIdStage($stage);
                $candidature->setDate(new \DateTimeImmutable('now'));
                $em->persist($candidature);
                $em->flush();
            }

            return $this->redirectToRoute('app_stage');
        }

        return $this->renderForm('candidature/new.html.twig', [
            'form' => $form,
            'stage' => $stage,
        ]);
    }

    #[Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_ENTREPRISE')")]
    #[Route('/candidature/{id}/update', name: 'app_candidature_update', requirements: ['id' => '\d+'])]
    public function update(Candidature $candidature, Request $request, ManagerRegistry $doctrine, SluggerInterface $slugger): Response
    {
        if ($this->getUser()->getId() !== $candidature->getAuthor()->getId()) {
            throw $this->createAccessDeniedException('Vous ne pouvez pas modifier cette candidature');
        }

        $em = $doctrine->getManager();

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
                $candidature->setDate(new \DateTimeImmutable('now'));
                $em->persist($candidature);
                $em->flush();
            }

            return $this->redirectToRoute('app_profil_stages');
        }

        return $this->render('candidature/update.html.twig', [
            'candidature' => $candidature,
            'form' => $form->createView(),
        ]);
    }
}
