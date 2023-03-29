<?php

namespace App\Controller;

use App\Entity\Candidature;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\SerializerInterface;

class GetCandidatureCollectionController extends AbstractController
{
    private Security $security;
    private $serializer;

    public function __construct(Security $security, SerializerInterface $serializer)
    {
        $this->security = $security;
        $this->serializer = $serializer;
    }

    public function __invoke(ManagerRegistry $doctrine): Response
    {
        if (!$this->security->isGranted('ROLE_ETUDIANT')) {
            throw new AccessDeniedException();
        }

        $user = $this->security->getUser();
        $candidatures = $doctrine->getRepository(Candidature::class)->findBy(['idUser' => $user]);

        $data = $this->serializer->serialize($candidatures, 'json', ['groups' => ['get_Candidature', 'get_Stage', 'get_User']]);

        return new Response($data, 200, ['Content-Type' => 'application/json']);
    }
}
