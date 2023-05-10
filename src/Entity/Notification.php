<?php

namespace App\Entity;

use App\Repository\NotificationRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints\Date;

#[ORM\Entity(repositoryClass: NotificationRepository::class)]
class Notification
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?string $content = null;

    #[ORM\ManyToOne(targetEntity: 'User', inversedBy: 'notification')]
    #[ORM\JoinColumn(name: 'recipient_id', referencedColumnName: 'id')]
    private ?User $notif_recipient = null;

    #[ORM\ManyToOne(targetEntity: 'User', inversedBy: 'notifications')]
    #[ORM\JoinColumn(name: 'sender_id', referencedColumnName: 'id')]
    private ?User $notif_sender = null;


    #[ORM\Column(nullable: true)]
    private ?DateTimeImmutable $updatedAt= null;

    #[ORM\Column(nullable: true)]
    private ?string $url=null;

    #[ORM\Column(nullable: true, unique: true)]
    private ?int $identifier=null;

    public function getId(): ?int
    {
        return $this->id;
    }
    public function getContent(){
        return $this->content;
    }

    public function setContent(?string $content):self
    {
        $this->content = $content;
        return $this;
    }
    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getNotifRecipient(): ?User
    {
        return $this->notif_recipient;
    }

    public function setNotifRecipient(?User $notif_recipient): self
    {
        $this->notif_recipient = $notif_recipient;

        return $this;
    }

    public function getNotifSender(): ?User
    {
        return $this->notif_sender;
    }

    public function setNotifSender(?User $notif_sender): self
    {
        $this->notif_sender = $notif_sender;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getIdentifier(): ?int
    {
        return $this->identifier;
    }

    public function setIdentifier(?int $identifier): self
    {
        $this->identifier = $identifier;

        return $this;
    }
}
