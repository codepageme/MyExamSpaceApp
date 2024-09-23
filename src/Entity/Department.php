<?php

namespace App\Entity;

use App\Repository\DepartmentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DepartmentRepository::class)]
class Department
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $department = null;

    /**
     * @var Collection<int, Classroom>
     */
    #[ORM\OneToMany(targetEntity: Classroom::class, mappedBy: 'department')]
    private Collection $Classroom;

    /**
     * @var Collection<int, Classroom>
     */
    #[ORM\OneToMany(targetEntity: Classroom::class, mappedBy: 'Department')]
    private Collection $classrooms;

    public function __construct()
    {
        $this->Classroom = new ArrayCollection();
        $this->classrooms = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getDepartment(): ?string
    {
        return $this->department;
    }

    public function setDepartment(string $department): static
    {
        $this->department = $department;

        return $this;
    }

    /**
     * @return Collection<int, Classroom>
     */
    public function getClassroom(): Collection
    {
        return $this->Classroom;
    }

    public function addClassroom(Classroom $classroom): static
    {
        if (!$this->Classroom->contains($classroom)) {
            $this->Classroom->add($classroom);
            $classroom->setDepartment($this);
        }

        return $this;
    }

    public function removeClassroom(Classroom $classroom): static

    {
        if ($this->Classroom->removeElement($classroom)) {
            // set the owning side to null (unless already changed)
            if ($classroom->getDepartment() === $this) {
                $classroom->setDepartment(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Classroom>
     */
    public function getClassrooms(): Collection
    {
        return $this->classrooms;
    }
}
