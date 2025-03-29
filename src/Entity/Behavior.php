<?php

namespace App\Entity;

use App\Repository\BehaviorRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BehaviorRepository::class)]
class Behavior
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

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $comment = null;

    #[ORM\Column(type: 'integer')]
    private ?int $rating = null; // Rating from 1 to 5 or 1 to 10

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

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;
        return $this;
    }

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function setRating(?int $rating): self
    {
        $this->rating = $rating;
        return $this;
    }
}
