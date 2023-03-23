<?php

namespace App\Entity;

use App\Repository\FaqRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;


#[ORM\Entity(repositoryClass: FaqRepository::class)]
#[ApiResource(normalizationContext: ['groups' => ['get_Faq', 'get_User']], order: ['title' => 'ASC'])]
#[ApiFilter(OrderFilter::class, properties: ['question', 'reponse'], arguments: ['orderParameterName' => 'order'])]
#[ApiFilter(SearchFilter::class, properties: ['question' => 'partial', 'reponse' => 'partial'])]
#[Get(normalizationContext: ['groups' => ['get_Faq']])]
#[GetCollection]


class Faq
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['get_Faq'])]
    private ?int $id = null;

    #[ORM\Column(length: 500)]
    #[Groups(['get_Faq'])]
    private ?string $question = null;

    #[ORM\Column(length: 500)]
    #[Groups(['get_Faq'])]
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
