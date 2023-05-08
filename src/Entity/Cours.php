<?php

namespace App\Entity;

use App\Repository\CoursRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: CoursRepository::class)]
#[Vich\Uploadable()]
class Cours implements \Serializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?string $courseName = null;


    #[Vich\UploadableField(mapping: 'course_file', fileNameProperty: 'courseName')]
    private ?File $courseFile = null;

    #[ORM\ManyToOne(targetEntity: 'Matiere', inversedBy: 'cours')]
    #[ORM\JoinColumn(name: 'matiere_id', referencedColumnName: 'id')]
    private ?Matiere $matiere = null;

    #[ORM\ManyToOne(targetEntity: 'User', inversedBy: 'cours')]
    #[ORM\JoinColumn(name: 'teacher_id', referencedColumnName: 'id')]
    private ?User $teacher = null;
    #[ORM\Column(nullable: true)]
    private ?DateTimeImmutable $updatedAt= null;

    #[ORM\Column(nullable: true)]
    private ?string $nom;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(){
        return $this->nom;
    }

    public function setNom(?string $nom){
        $this->nom = $nom;
    }

    public function getTeacher(){
        return $this->teacher;
    }

    public function setTeacher(?User $teacher){
        $this->teacher = $teacher;
    }

    public function getMatiere(): Matiere
    {
        return $this->matiere;
    }

    public function setMatiere(?Matiere $matiere): self
    {
        $this->matiere = $matiere;
        return $this;
    }

    public function getCourseName(){
        return $this->courseName;
}
    public function setCourseName($courseName){
        $this->courseName = $courseName;
    }

    public function setCourseFile(?File $courseFile = null): void
    {
        $this->courseFile = $courseFile;

        if (null !== $courseFile) {
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getCourseFile(): ?File
    {
        return $this->courseFile;
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
