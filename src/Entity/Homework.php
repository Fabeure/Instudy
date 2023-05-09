<?php

namespace App\Entity;

use App\Repository\HomeworkRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
#[ORM\Entity(repositoryClass: HomeworkRepository::class)]
#[Vich\Uploadable()]
class Homework
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;


    #[ORM\ManyToOne(targetEntity: 'User', inversedBy: 'homework')]
    #[ORM\JoinColumn(name: 'student_id', referencedColumnName: 'id')]
    private ?User $student = null;

    #[ORM\ManyToOne(targetEntity: 'User', inversedBy: 'homework')]
    #[ORM\JoinColumn(name: 'teacher_id', referencedColumnName: 'id')]
    private ?User $teacher = null;

    #[ORM\Column(nullable: true)]
    private ?int $grade = null;

    #[ORM\Column(nullable: true)]
    private ?string $commentaire = null;

    #[ORM\Column(nullable: true)]
    private ?string $homeworkName = null;


    #[Vich\UploadableField(mapping: 'homework_file', fileNameProperty: 'homeworkName')]
    private ?File $homeworkFile = null;


    #[ORM\Column(nullable: true)]
    private ?DateTimeImmutable $updatedAt= null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStudent(){
        return $this->student;
    }

    public function setStudent(?User $student){
        $this->student = $student;
    }
    public function getTeacher(){
        return $this->teacher;
    }

    public function setTeacher(?User $teacher){
        $this->teacher = $teacher;
    }
    public function getHomeworkName(){
        return $this->homeworkName;
    }
    public function setHomeworkName($homeworkName){
        $this->homeworkName = $homeworkName;
    }

    public function setHomeworkFile(?File $homeworkFile = null): void
    {
        $this->homeworkFile = $homeworkFile;

        if (null !== $homeworkFile) {
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getHomeworkFile(): ?File
    {
        return $this->homeworkFile;
    }

    public function getCommentaire(){
        return $this->commentaire;
    }

    public function setCommentaire(?string $commentaire): self
    {
        $this->commentaire = $commentaire;
        return $this;
    }
    public function serialize()
    {
        // TODO: Implement serialize() method.
    }

    public function unserialize(string $data)
    {
        // TODO: Implement unserialize() method.
    }

    public function __serialize(): array
    {

        return [
            'id' => $this->id
        ];
    }

    public function __unserialize(array $data): void
    {
        $this->id = $data['id'];
    }
}
