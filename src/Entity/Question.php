<?php

namespace App\Entity;

use App\Repository\QuestionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuestionRepository::class)]
class Question
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    #[ORM\ManyToOne(inversedBy: 'questions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?QuestionType $questionType = null; // Updated to QuestionType

    #[ORM\Column(length: 255)]
    private ?string $class = null;

    #[ORM\Column(length: 255)]
    private ?string $subject = null;

    #[ORM\ManyToOne(inversedBy: 'questions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Teacher $teacher = null; // Updated to Teacher

    #[ORM\ManyToOne(inversedBy: 'questions')]
    private ?Classroom $classroom = null; // Updated to classroom

    #[ORM\ManyToOne(inversedBy: 'questions')]
    private ?Department $department = null; // Updated to department

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getQuestionType(): ?QuestionType // Updated method name
    {
        return $this->questionType;
    }

    public function setQuestionType(?QuestionType $questionType): static // Updated method name
    {
        $this->questionType = $questionType;

        return $this;
    }

    public function getClass(): ?string
    {
        return $this->class;
    }

    public function setClass(string $class): static
    {
        $this->class = $class;

        return $this;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): static
    {
        $this->subject = $subject;

        return $this;
    }

    public function getTeacher(): ?Teacher // Updated method name
    {
        return $this->teacher;
    }

    public function setTeacher(?Teacher $teacher): static // Updated method name
    {
        $this->teacher = $teacher;

        return $this;
    }

    public function getClassroom(): ?Classroom // Updated method name
    {
        return $this->classroom;
    }

    public function setClassroom(?Classroom $classroom): static // Updated method name
    {
        $this->classroom = $classroom;

        return $this;
    }

    public function getDepartment(): ?Department // Updated method name
    {
        return $this->department;
    }

    public function setDepartment(?Department $department): static // Updated method name
    {
        $this->department = $department;

        return $this;
    }
}
