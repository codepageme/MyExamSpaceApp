<?php

namespace App\Entity;

use App\Repository\AttendanceRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AttendanceRepository::class)]
class Attendance
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Student::class)]
    private ?Student $student = null;

    #[ORM\ManyToOne(targetEntity: Classroom::class)]
    private ?Classroom $classroom = null;

    #[ORM\ManyToOne(targetEntity: Session::class)] // ✅ Replace AcademicCalendar
    private ?Session $session = null;

    #[ORM\ManyToOne(targetEntity: Term::class)] // ✅ Replace AcademicCalendar
    private ?Term $term = null;

    #[ORM\Column]
    private ?int $daysPresent = 0;

    #[ORM\Column]
    private ?int $daysAbsent = 0;

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

    public function getClassroom(): ?Classroom
    {
        return $this->classroom;
    }

    public function setClassroom(?Classroom $classroom): self
    {
        $this->classroom = $classroom;
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

    public function getDaysPresent(): int
    {
        return $this->daysPresent;
    }

    public function setDaysPresent(int $daysPresent): self
    {
        $this->daysPresent = $daysPresent;
        return $this;
    }

    public function getDaysAbsent(): int
    {
        return $this->daysAbsent;
    }

    public function setDaysAbsent(int $daysAbsent): self
    {
        $this->daysAbsent = $daysAbsent;
        return $this;
    }
}
