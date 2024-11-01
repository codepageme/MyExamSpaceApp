<?php

namespace App\Entity;

use App\Repository\SubjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SubjectRepository::class)]
class Subject
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Course = null;

    /**
     * @var Collection<int, Classroom>
     */
    #[ORM\ManyToMany(targetEntity: Classroom::class, mappedBy: 'Subject')]
    private Collection $classrooms;

    /**
     * @var Collection<int, TeacherSubject>
     */
    #[ORM\OneToMany(targetEntity: TeacherSubject::class, mappedBy: 'Subject', orphanRemoval: true)]
    private Collection $teacherSubjects;

   

    public function __construct()
    {
        $this->classrooms = new ArrayCollection();
        $this->teacherSubjects = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCourse(): ?string
    {
        return $this->Course;
    }

    public function setCourse(string $Course): static
    {
        $this->Course = $Course;

        return $this;
    }

    /**
     * @return Collection<int, Classroom>
     */
    public function getClassrooms(): Collection
    {
        return $this->classrooms;
    }

    public function addClassroom(Classroom $classroom): static
    {
        if (!$this->classrooms->contains($classroom)) {
            $this->classrooms->add($classroom);
            $classroom->addSubject($this);
        }

        return $this;
    }

    public function removeClassroom(Classroom $classroom): static
    {
        if ($this->classrooms->removeElement($classroom)) {
            $classroom->removeSubject($this);
        }

        return $this;
    }


    /**
     * @return Collection<int, TeacherSubject>
     */
    public function getTeacherSubjects(): Collection
    {
        return $this->teacherSubjects;
    }

    public function addTeacherSubject(TeacherSubject $teacherSubject): static
    {
        if (!$this->teacherSubjects->contains($teacherSubject)) {
            $this->teacherSubjects->add($teacherSubject);
            $teacherSubject->setSubject($this);
        }

        return $this;
    }

    public function removeTeacherSubject(TeacherSubject $teacherSubject): static
    {
        if ($this->teacherSubjects->removeElement($teacherSubject)) {
            // set the owning side to null (unless already changed)
            if ($teacherSubject->getSubject() === $this) {
                $teacherSubject->setSubject(null);
            }
        }

        return $this;
    }

}
