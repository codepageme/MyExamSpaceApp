<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommentRepository::class)]
class Comment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Student::class)]
    private ?Student $student = null;

    #[ORM\ManyToOne(targetEntity: Session::class)] // ✅ Fixed (Replaced AcademicCalendar)
    private ?Session $session = null;

    #[ORM\ManyToOne(targetEntity: Term::class)] // ✅ Fixed (Replaced AcademicCalendar)
    private ?Term $term = null;

    #[ORM\Column(type: 'text')]
    private ?string $teacherComment = null;

    // ✅ Getters and Setters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStudent(): ?Student
    {
        return $this->student;
    }

    public function setStudent(?Student $student): self
    {
        $this->student = $student;
        return $this;
    }

    public function getSession(): ?Session
    {
        return $this->session;
    }

    public function setSession(?Session $session): self
    {
        $this->session = $session;
        return $this;
    }

    public function getTerm(): ?Term
    {
        return $this->term;
    }

    public function setTerm(?Term $term): self
    {
        $this->term = $term;
        return $this;
    }

    public function getTeacherComment(): ?string
    {
        return $this->teacherComment;
    }

    public function setTeacherComment(?string $teacherComment): self
    {
        $this->teacherComment = $teacherComment;
        return $this;
    }
}
