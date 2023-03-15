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
use App\Repository\AlternanceRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: AlternanceRepository::class)]
#[ApiResource(normalizationContext: ['groups' => ['get_Alternance', 'get_User']], order: ['titre' => 'ASC'])]
#[ApiFilter(OrderFilter::class, properties: ['titre', 'description'], arguments: ['orderParameterName' => 'order'])]
#[ApiFilter(SearchFilter::class, properties: ['titre' => 'partial', 'description' => 'partial'])]
#[Get(normalizationContext: ['groups' => ['get_Alternance', 'get_User']])]
#[GetCollection]
#[Put(denormalizationContext: ['groups' => ['set_Alternance']], security: "is_granted('ROLE_ENTREPRISE') && object.getAuthor() == user")]
#[Patch(denormalizationContext: ['groups' => ['set_Alternance']], security: "is_granted('ROLE_ENTREPRISE') && object.getAuthor() == user")]
class Alternance
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['get_Alternance'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['get_Alternance', 'set_Alternance'])]
    private ?string $titre = null;

    #[ORM\Column(length: 255)]
    #[Groups(['get_Alternance', 'set_Alternance'])]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'alternances')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['get_Alternance', 'get_User'])]
    private ?User $author = null;

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
