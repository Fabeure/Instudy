<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index;

#[ORM\Entity(repositoryClass: MessageRepository::class)]
#[ORM\Index(name: "created_at_index", columns:["created_at"])]
#[ORM\HasLifecycleCallbacks]
class Message
{
    use Timestamp;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private string $content;


    #[ORM\ManyToOne(targetEntity:"User", inversedBy:"messages"  )]
    private $user;

    #[ORM\ManyToOne(targetEntity:"User", inversedBy:"messages"  )]
    private $conversation;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getConversation(): ?User
    {
        return $this->conversation;
    }

    public function setConversation(?User $conversation): self
    {
        $this->conversation = $conversation;

        return $this;
    }
}
