<?php

namespace App\Entity;

use App\Repository\MatiereRepository;
use Doctrine\Common\Collections\ArrayCollection;
use \Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MatiereRepository::class)]
class Matiere
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?string $matiereName = null;

    #[ORM\OneToMany(targetEntity: 'Cours', mappedBy: 'matiere')]
    private Collection $cours;

    #[ORM\OneToOne(targetEntity: 'User')]
    #[ORM\JoinColumn(name: 'teach_id', referencedColumnName: 'id')]
    private ?User $teacher;

    public function __construct()
    {
        $this->cours = new ArrayCollection();
    }




    public function getId(): ?int
    {
        return $this->id;
    }



    public function getTeacher(){
    return $this->teacher;
}

    public function setTeacher(?User $teacher){
        $this->teacher = $teacher;
    }
    /**
     * @return Collection
     */
    public function getCours(): Collection
    {
        return $this->cours;

    }

    public function setCours(Collection $cours): self
    {
        $this->cours = $cours;
        return $this;
    }

    public function getMatiereName(): ?string
    {
        return $this->matiereName;
    }

    public function setMatiereName(?string $matiereName)
    {
        $this->matiereName = $matiereName;
    }

    public function addCour(Cours $cour): self
    {
        if (!$this->cours->contains($cour)) {
            $this->cours->add($cour);
            $cour->setMatiere($this);
        }

        return $this;
    }

    public function removeCour(Cours $cour): self
    {
        if ($this->cours->removeElement($cour)) {
            // set the owning side to null (unless already changed)
            if ($cour->getMatiere() === $this) {
                $cour->setMatiere(null);
            }
        }

        return $this;
    }


}
