<?php

namespace App\Entity;

use App\Repository\TeacherSubjectClassroomRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TeacherSubjectClassroomRepository::class)]
#[ORM\Table(name: 'teacher_subject_classroom')]
class TeacherSubjectClassroom
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: TeacherSubject::class, inversedBy: "teacherSubjectClassrooms")]
    #[ORM\JoinColumn(nullable: false)]
    private TeacherSubject $teacherSubject;
    #[ORM\ManyToOne(targetEntity: Classroom::class)]
    #[ORM\JoinColumn(nullable: false)]
    private Classroom $classroom;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTeacherSubject(): TeacherSubject
    {
        return $this->teacherSubject;
    }

    public function setTeacherSubject(TeacherSubject $teacherSubject): self
    {
        $this->teacherSubject = $teacherSubject;
        return $this;
    }

    public function getClassroom(): Classroom
    {
        return $this->classroom;
    }

    public function setClassroom(Classroom $classroom): self
    {
        $this->classroom = $classroom;
        return $this;
    }
}
