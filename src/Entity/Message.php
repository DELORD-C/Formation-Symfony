<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MessageRepository::class)]
class Message
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    private ?User $sender = null;

    #[ORM\ManyToMany(targetEntity: User::class)]
    private Collection $target;

    #[ORM\Column(length: 255)]
    private ?string $subject = null;

    #[ORM\Column(length: 1000)]
    private ?string $body = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?bool $state = null;

    public function __construct()
    {
        $this->target = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
        $this->state = false;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSender(): ?User
    {
        return $this->sender;
    }

    public function setSender(?User $sender): self
    {
        $this->sender = $sender;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getTarget(): Collection
    {
        return $this->target;
    }

    public function addTarget(User $target): self
    {
        if (!$this->target->contains($target)) {
            $this->target->add($target);
        }

        return $this;
    }

    public function setTargets(Collection $targets): void
    {
        $this->target = $targets;
    }

    public function removeTarget(User $target): self
    {
        $this->target->removeElement($target);

        return $this;
    }

    public function emptyTargets(): void
    {
        $this->target = new ArrayCollection();
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): self
    {
        $this->subject = $subject;

        return $this;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(string $body): self
    {
        $this->body = $body;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function isState(): ?bool
    {
        return $this->state;
    }

    public function setState(bool $state): self
    {
        $this->state = $state;

        return $this;
    }
}
