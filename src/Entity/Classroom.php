<?php

namespace App\Entity;

use App\Repository\ClassroomRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClassroomRepository::class)]
class Classroom
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Classname = null;

    /**
     * @var Collection<int, Subject>
     */
    #[ORM\ManyToMany(targetEntity: Subject::class, inversedBy: 'classrooms')]
    private Collection $Subject;

    #[ORM\ManyToOne(inversedBy: 'classrooms')]
    private ?Department $department = null;

    /**
     * @var Collection<int, Student>
     */
    #[ORM\OneToMany(targetEntity: Student::class, mappedBy: 'classroom')]
    private Collection $students;

    /**
     * @var Collection<int, TeacherClassroom>
     */
    #[ORM\OneToMany(targetEntity: TeacherClassroom::class, mappedBy: 'classroom', orphanRemoval: true)]
    private Collection $teacherClassrooms;

    public function __construct()
    {
        $this->Subject = new ArrayCollection();
        $this->students = new ArrayCollection();
        $this->teacherClassrooms = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClassname(): ?string
    {
        return $this->Classname;
    }

    public function setClassname(string $Classname): static
    {
        $this->Classname = $Classname;

        return $this;
    }

    /**
     * @return Collection<int, Subject>
     */
    public function getSubject(): Collection
    {
        return $this->Subject;
    }

    public function addSubject(Subject $subject): static
    {
        if (!$this->Subject->contains($subject)) {
            $this->Subject->add($subject);
        }

        return $this;
    }

    public function removeSubject(Subject $subject): static
    {
        $this->Subject->removeElement($subject);

        return $this;
    }
    

    public function getDepartment(): ?Department
    {
        return $this->department;
    }

    public function setDepartment(?Department $department): static
    {
        $this->department = $department;

        return $this;
    }

    /**
     * @return Collection<int, Student>
     */
    public function getStudents(): Collection
    {
        return $this->students;
    }

    public function addStudent(Student $student): static
    {
        if (!$this->students->contains($student)) {
            $this->students->add($student);
            $student->setClassroom($this);
        }

        return $this;
    }

    public function removeStudent(Student $student): static
    {
        if ($this->students->removeElement($student)) {
            // set the owning side to null (unless already changed)
            if ($student->getClassroom() === $this) {
                $student->setClassroom(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, TeacherClassroom>
     */
    public function getTeacherClassrooms(): Collection
    {
        return $this->teacherClassrooms;
    }

    public function addTeacherClassroom(TeacherClassroom $teacherClassroom): static
    {
        if (!$this->teacherClassrooms->contains($teacherClassroom)) {
            $this->teacherClassrooms->add($teacherClassroom);
            $teacherClassroom->setClassroom($this);
        }

        return $this;
    }

    public function removeTeacherClassroom(TeacherClassroom $teacherClassroom): static
    {
        if ($this->teacherClassrooms->removeElement($teacherClassroom)) {
            // set the owning side to null (unless already changed)
            if ($teacherClassroom->getClassroom() === $this) {
                $teacherClassroom->setClassroom(null);
            }
        }

        return $this;
    }
}
