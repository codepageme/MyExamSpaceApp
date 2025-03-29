<?php

namespace App\Entity;

use App\Repository\ReportsheetRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReportsheetRepository::class)]
class Reportsheet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'reportsheets')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Student $student = null;

    /**
     * @var Collection<int, Classroom>
     */
    #[ORM\ManyToMany(targetEntity: Classroom::class, inversedBy: 'reportsheets')]
    private Collection $classroom;

    /**
     * @var Collection<int, AcademicCalender>
     */
    #[ORM\ManyToMany(targetEntity: AcademicCalender::class, inversedBy: 'reportsheets')]
    private Collection $AcademicCalender;

    #[ORM\Column]
    private ?int $Score = null;

    #[ORM\Column]
    private ?int $Escore = null;

    public function __construct()
    {
        $this->classroom = new ArrayCollection();
        $this->AcademicCalender = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStudent(): ?Student
    {
        return $this->student;
    }

    public function setStudent(?Student $student): static
    {
        $this->student = $student;

        return $this;
    }

    /**
     * @return Collection<int, Classroom>
     */
    public function getClassroom(): Collection
    {
        return $this->classroom;
    }

    public function addClassroom(Classroom $classroom): static
    {
        if (!$this->classroom->contains($classroom)) {
            $this->classroom->add($classroom);
        }

        return $this;
    }

    public function removeClassroom(Classroom $classroom): static
    {
        $this->classroom->removeElement($classroom);

        return $this;
    }

    /**
     * @return Collection<int, AcademicCalender>
     */
    public function getAcademicCalender(): Collection
    {
        return $this->AcademicCalender;
    }

    public function addAcademicCalender(AcademicCalender $academicCalender): static
    {
        if (!$this->AcademicCalender->contains($academicCalender)) {
            $this->AcademicCalender->add($academicCalender);
        }

        return $this;
    }

    public function removeAcademicCalender(AcademicCalender $academicCalender): static
    {
        $this->AcademicCalender->removeElement($academicCalender);

        return $this;
    }

    public function getScore(): ?int
    {
        return $this->Score;
    }

    public function setScore(int $Score): static
    {
        $this->Score = $Score;

        return $this;
    }

    public function getEscore(): ?int
    {
        return $this->Escore;
    }

    public function setEscore(int $Escore): static
    {
        $this->Escore = $Escore;

        return $this;
    }
}
