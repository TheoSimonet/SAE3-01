<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Post;
use App\Controller\CreateAlternanceController;
use App\Controller\CreateEventController;
use App\Controller\DeleteAlternanceController;
use App\Controller\DeleteEventController;
use App\Repository\EventRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Put;



#[ORM\Entity(repositoryClass: EventRepository::class)]
#[ApiResource(operations: [
    new Post(
        uriTemplate: '/event',
        controller: CreateEventController::class,
        openapiContext: [
            'summary' => "Création d'un event",
            'description' => "Permet la création d'un event par un utilisateur enseignant.",
            'responses' => [
                '201' => ['description' => 'Ressource crée'],
                '403' => ['description' => "Vous n'êtes pas autorisé à créer cette ressource (vous devez être enseignant)"],
            ],
        ],
        denormalizationContext: ['groups' => ['set_Event']],
        security: "is_granted('ROLE_ENSEIGNANT')"
    ),
    new Delete(
        uriTemplate: '/event/{id}',
        controller: DeleteEventController::class,
        openapiContext: [
            'summary' => "Suppression d'un event",
            'description' => "Permet la suppression d'un event par un enseignant.",
            'responses' => [
                '204' => ['description' => 'Ressource supprimée'],
                '403' => ['description' => "Vous n'êtes pas autorisé à supprimer cette ressource (vous devez être enseignant)"],
            ],
        ],
        security: "is_granted('ROLE_ENSEIGNANT')"
    )], normalizationContext: ['groups' => ['get_Event', 'get_User']], order: ['title' => 'ASC'])]
#[ApiFilter(OrderFilter::class, properties: ['title', 'text'], arguments: ['orderParameterName' => 'order'])]
#[ApiFilter(SearchFilter::class, properties: ['title' => 'partial', 'text' => 'partial'])]
#[Get(normalizationContext: ['groups' => ['get_Event', 'get_User']])]
#[GetCollection]
#[Put(denormalizationContext: ['groups' => ['set_Event']], security: "is_granted('ROLE_ENSEIGNANT')")]
#[Patch(denormalizationContext: ['groups' => ['set_Event']], security: "is_granted('ROLE_ENSEIGNANT')")]
class Event
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['get_Event'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['get_Event', 'set_Event'])]
    private ?string $title = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['get_Event', 'set_Event'])]
    private ?\DateTimeInterface $startTime = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['get_Event', 'set_Event'])]
    private ?\DateTimeInterface $endTime = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['get_Event', 'set_Event'])]
    private ?string $text = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getStartTime(): ?\DateTimeInterface
    {
        return $this->startTime;
    }

    public function setStartTime(\DateTimeInterface $startTime): self
    {
        $this->startTime = $startTime;

        return $this;
    }

    public function getEndTime(): ?\DateTimeInterface
    {
        return $this->endTime;
    }

    public function setEndTime(\DateTimeInterface $endTime): self
    {
        $this->endTime = $endTime;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }
}
