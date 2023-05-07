<?php

namespace App\Entity;

use App\Repository\MessagesRepository;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

#[ORM\Entity(repositoryClass: MessagesRepository::class)]
class Messages
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    #[ORM\ManyToOne(inversedBy: 'messages')]
    private ?User $client = null;

    #[ORM\ManyToOne(inversedBy: 'messages')]
    private ?User $prestataire = null;

    #[ORM\ManyToOne(inversedBy: 'messages')]
    private ?Messageries $messageries = null;

    public function __construct()
    {
        $this->createdAt = new DateTime();
        $this->updatedAt = new DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getClient(): ?User
    {
        return $this->client;
    }

    public function setClient(?User $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getPrestataire(): ?User
    {
        return $this->prestataire;
    }

    public function setPrestataire(?User $prestataire): self
    {
        $this->prestataire = $prestataire;

        return $this;
    }

    public function getMessageries(): ?Messageries
    {
        return $this->messageries;
    }

    public function setMessageries(?Messageries $messageries): self
    {
        $this->messageries = $messageries;

        return $this;
    }
}
