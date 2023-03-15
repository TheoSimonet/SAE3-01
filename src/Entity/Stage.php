<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Controller\CreateStageController;
use App\Controller\DeleteStageController;
use App\Repository\StageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(operations: [
    new Post(
        uriTemplate: '/stages',
        controller: CreateStageController::class,
        openapiContext: [
            'summary' => "Création d'un stage",
            'description' => "Permet la création d'un stage par un utilisateur membre d'une entreprise.",
            'responses' => [
                '201' => ['description' => 'Ressource crée'],
                '403' => ['description' => "Vous n'êtes pas autorisé à créer cette ressource (vous devez être membre d'une entreprise)"],
            ],
        ],
        denormalizationContext: ['groups' => ['set_Stage']],
        security: "is_granted('ROLE_ENTREPRISE')"
    ),
    new Delete(
        uriTemplate: '/stages/{id}',
        controller: DeleteStageController::class,
        openapiContext: [
            'summary' => "Suppression d'un stage",
            'description' => "Permet la suppression d'un stage par son auteur.",
            'responses' => [
                '204' => ['description' => 'Ressource supprimée'],
                '403' => ['description' => "Vous n'êtes pas autorisé à supprimer cette ressource (vous devez être l'auteur du stage)"],
            ],
        ],
        security: "is_granted('ROLE_ENTREPRISE')"
    )], normalizationContext: ['groups' => ['get_Stage', 'get_User']], order: ['titre' => 'ASC']
)]
#[ApiFilter(OrderFilter::class, properties: ['titre', 'description'], arguments: ['orderParameterName' => 'order'])]
#[ApiFilter(SearchFilter::class, properties: ['titre' => 'partial', 'description' => 'partial'])]
#[Get(normalizationContext: ['groups' => ['get_Stage', 'get_User']], security: "is_granted('ROLE_ENTREPRISE') || is_granted('ROLE_ETUDIANT')")]
#[GetCollection]
#[Put(denormalizationContext: ['groups' => ['set_Stage']], security: "is_granted('ROLE_ENTREPRISE') && object.getAuthor() == user")]
#[Patch(denormalizationContext: ['groups' => ['set_Stage']], security: "is_granted('ROLE_ENTREPRISE') && object.getAuthor() == user")]
#[ORM\Entity(repositoryClass: StageRepository::class)]
class Stage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['get_Stage'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['get_Stage', 'set_Stage'])]
    private ?string $titre = null;

    #[ORM\Column(length: 255)]
    #[Groups(['get_Stage', 'set_Stage'])]
    private ?string $description = null;

    #[ORM\OneToMany(mappedBy: 'idStage', targetEntity: Candidature::class, orphanRemoval: true)]
    private Collection $candidatures;

    #[ORM\ManyToOne(inversedBy: 'stages')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['get_Stage', 'get_User'])]
    private ?User $author = null;

    public function __construct()
    {
        $this->candidatures = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, Candidature>
     */
    public function getCandidatures(): Collection
    {
        return $this->candidatures;
    }

    public function addCandidature(Candidature $candidature): self
    {
        if (!$this->candidatures->contains($candidature)) {
            $this->candidatures->add($candidature);
            $candidature->setIdStage($this);
        }

        return $this;
    }

    public function removeCandidature(Candidature $candidature): self
    {
        if ($this->candidatures->removeElement($candidature)) {
            // set the owning side to null (unless already changed)
            if ($candidature->getIdStage() === $this) {
                $candidature->setIdStage(null);
            }
        }

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }
}
