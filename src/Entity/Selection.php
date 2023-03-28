<?php

namespace App\Entity;

use ApiPlatform\Metadata\Get;
use App\Repository\SelectionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: SelectionRepository::class)]
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
