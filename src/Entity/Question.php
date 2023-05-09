<?php

namespace App\Entity;

use App\Repository\QuestionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: QuestionRepository::class)]
#[UniqueEntity(fields: ['content'], message: 'This question already exists')]
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

    #[ORM\ManyToOne(targetEntity: 'Cours', inversedBy: 'questions')]
    #[ORM\JoinColumn(name: 'course_id', referencedColumnName: 'id')]
    private ?Cours $cours = null;

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
    public function getCourse(){
        return $this->cours;
    }

    public function setCourse(?Cours $cours): self
    {
        $this->cours = $cours;
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

    public function getCours(): ?Cours
    {
        return $this->cours;
    }

    public function setCours(?Cours $cours): self
    {
        $this->cours = $cours;

        return $this;
    }
}
