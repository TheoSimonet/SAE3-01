<?php

namespace App\Entity;

use App\Controller\CreateFaqController;
use App\Controller\DeleteFaqController;
use App\Repository\FaqRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Delete;







#[ORM\Entity(repositoryClass: FaqRepository::class)]
#[ApiResource(operations: [
    new Post(
        uriTemplate: '/faqs',
        controller: CreateFaqController::class,
        openapiContext: [
            'summary' => "Création d'un event",
            'description' => "Permet la création d'un event par un utilisateur enseignant.",
            'responses' => [
                '201' => ['description' => 'Ressource crée'],
                '403' => ['description' => "Vous n'êtes pas autorisé à créer cette ressource (vous devez être enseignant)"],
            ],
        ],
        denormalizationContext: ['groups' => ['set_Faq']],
        security: "is_granted('ROLE_ENSEIGNANT') || is_granted('ROLE_ADMIN')"
    ),
    new Delete(
        uriTemplate: '/faqs/{id}',
        controller: DeleteFaqController::class,
        openapiContext: [
            'summary' => "Suppression d'un event",
            'description' => "Permet la suppression d'un event par un enseignant.",
            'responses' => [
                '204' => ['description' => 'Ressource supprimée'],
                '403' => ['description' => "Vous n'êtes pas autorisé à supprimer cette ressource (vous devez être enseignant)"],
            ],
        ],
        security: "is_granted('ROLE_ENSEIGNANT') || is_granted('ROLE_ADMIN')"
    )], normalizationContext: ['groups' => ['get_Faq', 'get_User']], order: ['question' => 'ASC'])]
#[ApiFilter(OrderFilter::class, properties: ['question', 'reponse'], arguments: ['orderParameterName' => 'order'])]
#[ApiFilter(SearchFilter::class, properties: ['question' => 'partial', 'reponse' => 'partial'])]
#[Get(normalizationContext: ['groups' => ['get_Faq']])]
#[GetCollection]
#[Put(denormalizationContext: ['groups' => ['set_Faq']], security: "is_granted('ROLE_ADMIN') || is_granted('ROLE_ENSEIGNANT')")]
#[Patch(denormalizationContext: ['groups' => ['set_Faq']], security: "is_granted('ROLE_ADMIN') || is_granted('ROLE_ENSEIGNANT')")]

class Faq
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['get_Faq', 'set_Faq'])]
    private ?int $id = null;

    #[ORM\Column(length: 500)]
    #[Groups(['get_Faq', 'set_Faq'])]
    private ?string $question = null;

    #[ORM\Column(length: 500)]
    #[Groups(['get_Faq', 'set_Faq'])]
    private ?string $reponse = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuestion(): ?string
    {
        return $this->question;
    }

    public function setQuestion(string $question): self
    {
        $this->question = $question;

        return $this;
    }

    public function getReponse(): ?string
    {
        return $this->reponse;
    }

    public function setReponse(string $reponse): self
    {
        $this->reponse = $reponse;

        return $this;
    }
}
