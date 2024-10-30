<?php

namespace App\Entity;

use App\Repository\TeacherClassroomRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TeacherClassroomRepository::class)]
class TeacherClassroom
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'teacherClassrooms')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Teacher $teacher = null; // Changed 'teacher' to 'Teacher'

    #[ORM\ManyToOne(inversedBy: 'teacherClassrooms')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Classroom $classroom = null; // Changed 'classroom' to 'Classroom'

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTeacher(): ?Teacher
    {
        return $this->teacher;
    }

    public function setTeacher(?Teacher $teacher): self
    {
        $this->teacher = $teacher;
        return $this;
    }

    public function getClassroom(): ?Classroom
    {
        return $this->classroom;
    }

    public function setClassroom(?Classroom $classroom): self
    {
        $this->classroom = $classroom;
        return $this;
    }
}
