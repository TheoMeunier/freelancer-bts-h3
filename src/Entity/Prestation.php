<?php

namespace App\Entity;

use App\Repository\PrestationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Validator\Constraints\DateTime;

#[ORM\Entity(repositoryClass: PrestationRepository::class)]
class Prestation
{

    use TimestampableEntity;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'prestations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\OneToMany(mappedBy: 'name', targetEntity: Gategories::class)]
    private Collection $gategories;

    /**
     * Ajoute la date lors de la creation / modification d'un prestation
     */
    public function __construct()
    {
        $this->createdAt = new DateTime();
        $this->updatedAt = new DateTime();
        $this->gategories = new ArrayCollection();
    }

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getUserId(): ?User
    {
        return $this->user;
    }

    public function setUserId(?User $user_id): self
    {
        $this->user = $user_id;

        return $this;
    }

    /**
     * @return Collection<int, Gategories>
     */
    public function getGategories(): Collection
    {
        return $this->gategories;
    }

    public function addGategory(Gategories $gategory): self
    {
        if (!$this->gategories->contains($gategory)) {
            $this->gategories->add($gategory);
            $gategory->setName($this);
        }

        return $this;
    }

    public function removeGategory(Gategories $gategory): self
    {
        if ($this->gategories->removeElement($gategory)) {
            // set the owning side to null (unless already changed)
            if ($gategory->getName() === $this) {
                $gategory->setName(null);
            }
        }

        return $this;
    }
}
