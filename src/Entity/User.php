<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Controller\GetMeController;
use App\Controller\SecurityController;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(
            uriTemplate: '/me',
            controller: GetMeController::class,
            openapiContext: [
                'summary' => "Récupérer l'utilisateur courant",
                'description' => "Permet de récupérer l'utilisateur courant ou de retourner une erreur si aucun utilisateur n'est connecté.",
                'responses' => [
                    '200' => ['description' => "Récupération de l'utilisateur courant : OK"],
                    '401' => ['description' => "/!\ Pas d'utilisateur courant"],
                ],
            ],
            paginationEnabled: false,
            normalizationContext: ['groups' => ['get_Me', 'get_User']],
            security: "is_granted('ROLE_USER')"
        ),
        new Post(
            uriTemplate: '/login',
            controller: SecurityController::class,
            openapiContext: [
                'tags' => ['Authentification'],
                'summary' => "Authentification d'un utilisateur",
                'description' => "Permet à un utilisateur de s'authentifier.",
                'responses' => [
                    '200' => ['description' => 'Authentification réussie'],
                    '401' => ['description' => 'Authentification échouée'],
                ],
                'requestBody' => [
                    'content' => [
                        'application/json' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'username' => [
                                        'type' => 'string',
                                        'example' => 'test@gmail.com',
                                    ],
                                    'password' => [
                                        'type' => 'string',
                                        'example' => 'MonMotDePasse',
                                    ],
                                ],
                            ],
                            'example' => [
                                'username' => 'test@gmail.com',
                                'password' => 'MonMotDePasse',
                            ],
                        ],
                    ],
                ],

            ],
            normalizationContext: ['groups' => ['get_User']],
            denormalizationContext: ['groups' => ['login']],
        ),
        new Post(
            uriTemplate: '/logout',
            controller: SecurityController::class,
            openapiContext: [
                'tags' => ['Authentification'],
                'summary' => "Déconnexion d'un utilisateur",
                'description' => "Permet à un utilisateur de se déconnecter.",
                'responses' => [
                    '200' => ['description' => 'Déconnexion réussie'],
                ],
            ],
        )
    ],
    normalizationContext: ['groups' => ['get_User']])]
#[Get(normalizationContext: ['groups' => ['get_User']])]
#[Put(denormalizationContext: ['groups' => ['set_User']], security: "is_granted('IS_AUTHENTICATED_FULLY') && object == user")]
#[Patch(denormalizationContext: ['groups' => ['set_User']], security: "is_granted('IS_AUTHENTICATED_FULLY') && object == user")]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['get_User'])]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Groups(['get_User', 'set_User'])]
    #[Assert\Email]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Groups(['set_User'])]
    private ?string $password = null;

    #[ORM\Column(length: 30)]
    #[Groups(['get_User', 'set_User'])]
    #[Assert\Regex('/^[^<>&"]+$/')]
    private ?string $firstname = null;

    #[ORM\Column(length: 40)]
    #[Groups(['get_User', 'set_User'])]
    #[Assert\Regex('/^[^<>&"]+$/')]
    private ?string $lastname = null;

    #[ORM\Column(length: 20)]
    #[Groups(['get_User', 'set_User'])]
    private ?string $phone = null;

    #[ORM\OneToMany(mappedBy: 'idUser', targetEntity: Candidature::class, orphanRemoval: true)]
    private Collection $candidatures;

    #[ORM\OneToMany(mappedBy: 'sender_id', targetEntity: Message::class, orphanRemoval: true)]
    private Collection $messages;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Conversation::class, orphanRemoval: true)]
    private Collection $conversations;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Stage::class, orphanRemoval: true)]
    private Collection $stages;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Alternance::class)]
    private Collection $alternances;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: ProjetTER::class, orphanRemoval: true)]
    private Collection $projetTERs;

    #[ORM\OneToMany(mappedBy: 'idUser', targetEntity: Selection::class, orphanRemoval: true)]
    private Collection $selections;

    public function __construct()
    {
        $this->candidatures = new ArrayCollection();
        $this->messages = new ArrayCollection();
        $this->conversations = new ArrayCollection();
        $this->stages = new ArrayCollection();
        $this->alternances = new ArrayCollection();
        $this->projetTERs = new ArrayCollection();
        $this->selections = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

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
            $candidature->setIdUser($this);
        }

        return $this;
    }

    public function removeCandidature(Candidature $candidature): self
    {
        if ($this->candidatures->removeElement($candidature)) {
            // set the owning side to null (unless already changed)
            if ($candidature->getIdUser() === $this) {
                $candidature->setIdUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Message>
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages->add($message);
            $message->setSenderId($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): self
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getSenderId() === $this) {
                $message->setSenderId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Conversation>
     */
    public function getConversations(): Collection
    {
        return $this->conversations;
    }

    public function addConversation(Conversation $conversation): self
    {
        if (!$this->conversations->contains($conversation)) {
            $this->conversations->add($conversation);
            $conversation->setAuthor($this);
        }

        return $this;
    }

    public function removeConversation(Conversation $conversation): self
    {
        if ($this->conversations->removeElement($conversation)) {
            // set the owning side to null (unless already changed)
            if ($conversation->getAuthor() === $this) {
                $conversation->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Stage>
     */
    public function getStages(): Collection
    {
        return $this->stages;
    }

    public function addStage(Stage $stage): self
    {
        if (!$this->stages->contains($stage)) {
            $this->stages->add($stage);
            $stage->setAuthor($this);
        }

        return $this;
    }

    public function removeStage(Stage $stage): self
    {
        if ($this->stages->removeElement($stage)) {
            // set the owning side to null (unless already changed)
            if ($stage->getAuthor() === $this) {
                $stage->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Alternance>
     */
    public function getAlternances(): Collection
    {
        return $this->alternances;
    }

    public function addAlternance(Alternance $alternance): self
    {
        if (!$this->alternances->contains($alternance)) {
            $this->alternances->add($alternance);
            $alternance->setAuthor($this);
        }

        return $this;
    }

    public function removeAlternance(Alternance $alternance): self
    {
        if ($this->alternances->removeElement($alternance)) {
            // set the owning side to null (unless already changed)
            if ($alternance->getAuthor() === $this) {
                $alternance->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ProjetTER>
     */
    public function getProjetTERs(): Collection
    {
        return $this->projetTERs;
    }

    public function addProjetTER(ProjetTER $projetTER): self
    {
        if (!$this->projetTERs->contains($projetTER)) {
            $this->projetTERs->add($projetTER);
            $projetTER->setAuthor($this);
        }

        return $this;
    }

    public function removeProjetTER(ProjetTER $projetTER): self
    {
        if ($this->projetTERs->removeElement($projetTER)) {
            // set the owning side to null (unless already changed)
            if ($projetTER->getAuthor() === $this) {
                $projetTER->setAuthor(null);
            }
        }

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
            $selection->setIdUser($this);
        }

        return $this;
    }

    public function removeSelection(Selection $selection): self
    {
        if ($this->selections->removeElement($selection)) {
            // set the owning side to null (unless already changed)
            if ($selection->getIdUser() === $this) {
                $selection->setIdUser(null);
            }
        }

        return $this;
    }
}
