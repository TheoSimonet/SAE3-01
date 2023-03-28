<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Controller\GetSelectionCollectionController;
use App\Repository\SelectionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: SelectionRepository::class)]
#[ApiResource(operations: [
    new GetCollection(
        uriTemplate: '/selections',
        controller: GetSelectionCollectionController::class,
        openapiContext: [
            'summary' => "Récupération des sélections effectuées par l'utilisateur connecté",
            'description' => "Permet la récupération des sélections effectuées par l'utilisateur connecté.",
            'responses' => [
                '200' => ['description' => 'Sélection(s) trouvée(s)'],
                '401' => ['description' => "Vous n'êtes pas autorisé à voir cette ressource (vous devez être le créateur de cette sélection)"],
                '403' => ['description' => "Vous n'êtes pas autorisé à voir cette ressource (vous devez être le créateur de cette sélection)"],
            ],
        ],
        paginationEnabled: false,
        normalizationContext: ['groups' => ['get_Selection', 'get_Projet', 'get_User']],
    )])]
#[Get(normalizationContext: ['groups' => ['get_Selection', 'get_Projet', 'get_User']],
    security: "(is_granted('ROLE_ENSEIGNANT') && object.getIdProjet().getAuthor() == user) || (is_granted('ROLE_ETUDIANT') && object.getIdUser().getId() == user.getId())")]
class Selection
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['get_Selection'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'selections')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['get_Selection', 'get_Projet'])]
    private ?ProjetTER $idProjet = null;

    #[ORM\ManyToOne(inversedBy: 'selections')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['get_Selection', 'get_User'])]
    private ?User $idUser = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['get_Selection'])]
    private ?\DateTimeInterface $date = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdProjet(): ?ProjetTER
    {
        return $this->idProjet;
    }

    public function setIdProjet(?ProjetTER $idProjet): self
    {
        $this->idProjet = $idProjet;

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

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }
}
