<?php

namespace App\Entity;

use App\Repository\QuestionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuestionRepository::class)]
class Question
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $content = null;

    #[ORM\ManyToOne(targetEntity: 'User', inversedBy: 'questions')]
    #[ORM\JoinColumn(name: 'sender_id', referencedColumnName: 'id')]
    private ?User $sender = null;

    #[ORM\ManyToOne(targetEntity: 'Matiere', inversedBy: 'questions')]
    #[ORM\JoinColumn(name: 'course_id', referencedColumnName: 'id')]
    private ?Matiere $matiere = null;

    #[ORM\Column(nullable:true)]
    private ?string $response=null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getResponse(){
        return $this->response;
    }

    public function setResponse(?string $response): self
    {
        $this->response = $response;
        return $this;
    }
    public function getSender(){
        return $this->sender;
    }

    public function setSender(?User $sender): self
    {
        $this->sender = $sender;
        return $this;
    }
    public function getMatiere(){
        return $this->matiere;
    }

    public function setMatiere(?Matiere $matiere): self
    {
        $this->matiere = $matiere;
        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }
}
