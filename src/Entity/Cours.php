<?php

namespace App\Entity;

use App\Repository\CoursRepository;
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

    public function getId(): ?int
    {
        return $this->id;
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
