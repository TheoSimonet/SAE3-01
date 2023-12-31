<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Controller\DeleteCandidatureController;
use App\Controller\DeleteSelectionController;
use App\Controller\GetCandidatureCollectionController;
use App\Repository\CandidatureRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CandidatureRepository::class)]
#[ApiResource(operations: [
    new GetCollection(
        uriTemplate: '/candidatures',
        controller: GetCandidatureCollectionController::class,
        openapiContext: [
            'summary' => "Récupération des candidatures créées par l'utilisateur connecté",
            'description' => "Permet la récupération des candidatures créées par l'utilisateur connecté.",
            'responses' => [
                '200' => ['description' => 'Candidature(s) trouvée(s)'],
                '401' => ['description' => "Vous n'êtes pas autorisé à voir cette ressource (vous devez être le créateur de cette candidature)"],
                '403' => ['description' => "Vous n'êtes pas autorisé à voir cette ressource (vous devez être le créateur de cette candidature)"],
            ],
        ],
        paginationEnabled: false,
        normalizationContext: ['groups' => ['get_Candidature', 'get_Stage', 'get_User']]
    ),
    new Delete(
        uriTemplate: '/candidatures/{id}',
        controller: DeleteCandidatureController::class,
        openapiContext: [
            'summary' => "Suppression d'une candidature à un stage",
            'description' => "Permet la suppression d'une candidature à un stage par son auteur.",
            'responses' => [
                '204' => ['description' => 'Ressource supprimée'],
                '403' => ['description' => "Vous n'êtes pas autorisé à supprimer cette ressource (vous devez être l'auteur de la candidature)"],
            ],
        ],
        security: "is_granted('ROLE_ETUDIANT')"
    )])]
#[Get(normalizationContext: ['groups' => ['get_Candidature', 'get_Stage', 'get_User']],
    security: "(is_granted('ROLE_ENTREPRISE') && object.getIdStage().getAuthor() == user) || (is_granted('ROLE_ETUDIANT') && object.getIdUser().getId() == user.getId())")]
class Candidature
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['get_Candidature'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'candidatures')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['get_Candidature', 'get_Stage'])]
    private ?Stage $idStage = null;

    #[ORM\ManyToOne(inversedBy: 'candidatures')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['get_Candidature', 'get_User'])]
    private ?User $idUser = null;

    #[ORM\Column(length: 255)]
    #[Groups(['get_Candidature'])]
    private ?string $cvFilename = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['get_Candidature'])]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column]
    #[Groups(['get_Candidature'])]
    private ?bool $retenue = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdStage(): ?Stage
    {
        return $this->idStage;
    }

    public function setIdStage(?Stage $idStage): self
    {
        $this->idStage = $idStage;

        return $this;
    }

    public function getIdUser(): ?User
    {
        return $this->idUser;
    }

    public function setIdUser(?User $idUser): self
    {
        $this->idUser = $idUser;

        return $this;
    }

    public function getCvFilename(): ?string
    {
        return $this->cvFilename;
    }

    public function setCvFilename(string $cvFilename): self
    {
        $this->cvFilename = $cvFilename;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function isRetenue(): ?bool
    {
        return $this->retenue;
    }

    public function setRetenue(bool $retenue): self
    {
        $this->retenue = $retenue;

        return $this;
    }
}
