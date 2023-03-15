<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Put;
use App\Controller\UpdateProjetTERController;
use App\Repository\ProjetTERRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;

#[ORM\Entity(repositoryClass: ProjetTERRepository::class)]
#[ApiResource(operations : [
    new Put(
        uriTemplate: '/projet_t_e_rs/{id}',
        controller: UpdateProjetTERController::class,
        openapiContext: [
            'summary' => "Modification d'un projet TER",
            'description' => "Permet la modification d'un projet TER par un utilisateur enseignant.",
            'responses' => [
                '200' => ['description' => 'Ressource modifiée'],
                '403' => ['description' => "Vous n'êtes pas autorisé à modifier cette ressource (vous devez être enseignant)"],
            ],
        ],
        denormalizationContext: ['groups' => ['set_ProjetTER']],
        security: "is_granted('ROLE_ENSEIGNANT')"
    ),
    new Patch(
        uriTemplate: '/projet_t_e_rs/{id}',
        controller: UpdateProjetTERController::class,
        openapiContext: [
            'summary' => "Modification d'un projet TER",
            'description' => "Permet la modification d'un projet TER par un utilisateur enseignant.",
            'responses' => [
                '200' => ['description' => 'Ressource modifiée'],
                '403' => ['description' => "Vous n'êtes pas autorisé à modifier cette ressource (vous devez être enseignant)"],
            ],
        ],
        denormalizationContext: ['groups' => ['set_ProjetTER']],
        security: "is_granted('ROLE_ENSEIGNANT')"
    ),
], normalizationContext: ['groups' => ['get_ProjetTER', 'get_User']], order: ['date' => 'DESC'])]
#[ApiFilter(OrderFilter::class, properties: ['titre', 'description'], arguments: ['orderParameterName' => 'order'])]
#[ApiFilter(SearchFilter::class, properties: ['titre' => 'partial', 'description' => 'partial'])]
#[Get(normalizationContext: ['groups' => ['get_ProjetTER', 'get_User']])]
#[GetCollection]
class ProjetTER
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['get_ProjetTER'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['get_ProjetTER', 'set_ProjetTER'])]
    private ?string $titre = null;

    #[ORM\Column(length: 255)]
    #[Groups(['get_ProjetTER', 'set_ProjetTER'])]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'projetTERs')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['get_ProjetTER', 'get_User'])]
    private ?User $author = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['get_ProjetTER'])]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 255)]
    #[Groups(['get_ProjetTER', 'set_ProjetTER'])]
    private ?string $libProjet = null;

    #[ORM\OneToMany(mappedBy: 'idProjet', targetEntity: Selection::class, orphanRemoval: true)]
    private Collection $selections;

    public function __construct()
    {
        $this->selections = new ArrayCollection();
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

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('titre', new Assert\NotBlank());
        $metadata->addPropertyConstraint('description', new Assert\NotBlank());
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

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getLibProjet(): ?string
    {
        return $this->libProjet;
    }

    public function setLibProjet(string $libProjet): self
    {
        $this->libProjet = $libProjet;

        return $this;
    }

    /**
     * @return Collection<int, Selection>
     */
    public function getSelections(): Collection
    {
        return $this->selections;
    }

    public function addSelection(Selection $selection): self
    {
        if (!$this->selections->contains($selection)) {
            $this->selections->add($selection);
            $selection->setIdProjet($this);
        }

        return $this;
    }

    public function removeSelection(Selection $selection): self
    {
        if ($this->selections->removeElement($selection)) {
            // set the owning side to null (unless already changed)
            if ($selection->getIdProjet() === $this) {
                $selection->setIdProjet(null);
            }
        }

        return $this;
    }
}
