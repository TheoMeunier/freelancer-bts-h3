<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\UserRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Scheb\TwoFactorBundle\Model\Email\TwoFactorInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface, TwoFactorInterface
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private string $email;

    #[ORM\Column(length: 180)]
    private string $name;

    #[ORM\Column]
    private array $roles = [];

    #[ORM\Column]
    private string $password;

    #[ORM\Column(type: 'boolean')]
    private bool $isVerified = false;

    #[ORM\Column(type: 'string', length: 180, nullable: true)]
    private ?string $authCode = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Prestation::class)]
    private Collection $prestations;

    #[ORM\OneToMany(mappedBy: 'client', targetEntity: Like::class)]
    private Collection $likes;

    #[ORM\OneToOne(mappedBy: 'user', cascade: ['persist', 'remove'])]
    private ?InformationUser $informationUser = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: PrestationComments::class)]
    private Collection $prestation_comments;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Messagerie::class)]
    private Collection $messageries;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Message::class)]
    private Collection $messages;

    #[ORM\OneToMany(mappedBy: 'seeder', targetEntity: Messagerie::class, orphanRemoval: true)]
    private Collection $messageries_seeder;

    public function __construct()
    {
        $this->createdAt = new DateTime();
        $this->updatedAt = new DateTime();
        $this->prestations = new ArrayCollection();
        $this->likes = new ArrayCollection();
        $this->prestation_comments = new ArrayCollection();
        $this->messageries = new ArrayCollection();
        $this->messageries_seeder = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->getName();
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
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    /**
     * @return Collection<int, Prestation>
     */
    public function getPrestations(): Collection
    {
        return $this->prestations;
    }

    public function addPrestation(Prestation $prestation): self
    {
        if (!$this->prestations->contains($prestation)) {
            $this->prestations->add($prestation);
            $prestation->setUser($this);
        }

        return $this;
    }

    public function removePrestation(Prestation $prestation): self
    {
        if ($this->prestations->removeElement($prestation)) {
            // set the owning side to null (unless already changed)
            if ($prestation->getUser() === $this) {
                $prestation->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Like>
     */
    public function getLikes(): Collection
    {
        return $this->likes;
    }

    public function addLike(Like $like): self
    {
        if (!$this->likes->contains($like)) {
            $this->likes->add($like);
            $like->setClient($this);
        }

        return $this;
    }

    public function removeLike(Like $like): self
    {
        if ($this->likes->removeElement($like)) {
            // set the owning side to null (unless already changed)
            if ($like->getClient() === $this) {
                $like->setClient(null);
            }
        }

        return $this;
    }

    public function isEmailAuthEnabled(): bool
    {
        return true;
    }

    public function getEmailAuthRecipient(): string
    {
        return $this->email;
    }

    public function getEmailAuthCode(): string
    {
        if (null === $this->authCode) {
            throw new \LogicException('The email authentication code was not set');
        }

        return $this->authCode;
    }

    public function setEmailAuthCode(string $authCode): void
    {
        $this->authCode = $authCode;
    }

    public function getInformationUser(): ?InformationUser
    {
        return $this->informationUser;
    }

    public function setInformationUser(?InformationUser $informationUser): self
    {
        // unset the owning side of the relation if necessary
        if ($informationUser === null && $this->informationUser !== null) {
            $this->informationUser->setUser(null);
        }

        // set the owning side of the relation if necessary
        if ($informationUser !== null && $informationUser->getUser() !== $this) {
            $informationUser->setUser($this);
        }

        $this->informationUser = $informationUser;

        return $this;
    }

    /**
     * @return Collection<int, PrestationComments>
     */
    public function getPrestationComments(): Collection
    {
        return $this->prestation_comments;
    }

    public function addPrestationComment(PrestationComments $prestationComment): self
    {
        if (!$this->prestation_comments->contains($prestationComment)) {
            $this->prestation_comments->add($prestationComment);
            $prestationComment->setUser($this);
        }

        return $this;
    }

    public function removePrestationComment(PrestationComments $prestationComment): self
    {
        if ($this->prestation_comments->removeElement($prestationComment)) {
            // set the owning side to null (unless already changed)
            if ($prestationComment->getUser() === $this) {
                $prestationComment->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Messagerie>
     */
    public function getMessageries(): Collection
    {
        return $this->messageries;
    }

    public function addMessagery(Messagerie $messagery): self
    {
        if (!$this->messageries->contains($messagery)) {
            $this->messageries->add($messagery);
            $messagery->setUser($this);
        }

        return $this;
    }

    public function removeMessagery(Messagerie $messagery): self
    {
        if ($this->messageries->removeElement($messagery)) {
            // set the owning side to null (unless already changed)
            if ($messagery->getUser() === $this) {
                $messagery->setUser(null);
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
            $message->setUser($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): self
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getUser() === $this) {
                $message->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Messagerie>
     */
    public function getMessageriesSeeder(): Collection
    {
        return $this->messageries_seeder;
    }

    public function addMessageriesSeeder(Messagerie $messageriesSeeder): self
    {
        if (!$this->messageries_seeder->contains($messageriesSeeder)) {
            $this->messageries_seeder->add($messageriesSeeder);
            $messageriesSeeder->setSeeder($this);
        }

        return $this;
    }

    public function removeMessageriesSeeder(Messagerie $messageriesSeeder): self
    {
        if ($this->messageries_seeder->removeElement($messageriesSeeder)) {
            // set the owning side to null (unless already changed)
            if ($messageriesSeeder->getSeeder() === $this) {
                $messageriesSeeder->setSeeder(null);
            }
        }

        return $this;
    }
}
