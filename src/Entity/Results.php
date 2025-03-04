<?php

namespace App\Entity;

use App\Repository\ResultsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ResultsRepository::class)]
class Results
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'results')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Student $Student = null;

    #[ORM\ManyToOne(inversedBy: 'results')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Exam $Exam = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: 2)]
    private ?string $Score = null;

    #[ORM\ManyToOne(inversedBy: 'results')]
    #[ORM\JoinColumn(nullable: false)]
    private ?AcademicCalender $AcademicCalender = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $Date = null;

    #[ORM\Column]
    private ?float $Percentage = null;

    #[ORM\Column]
    private ?int $totalQuestions = null;

    #[ORM\Column]
    private ?int $answeredQuestions = null;

    #[ORM\Column]
    private ?int $correctAnswers = null;

    #[ORM\ManyToOne(inversedBy: 'results')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Grade $Grade = null;

    #[ORM\Column(nullable: true)]
    private ?int $Theory = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStudent(): ?Student
    {
        return $this->Student;
    }

    public function setStudent(?Student $Student): static
    {
        $this->Student = $Student;

        return $this;
    }

    public function getExam(): ?Exam
    {
        return $this->Exam;
    }

    public function setExam(?Exam $Exam): static
    {
        $this->Exam = $Exam;

        return $this;
    }

    public function getScore(): ?string
    {
        return $this->Score;
    }

    public function setScore(string $Score): static
    {
        $this->Score = $Score;

        return $this;
    }

    public function getAcademicCalender(): ?AcademicCalender
    {
        return $this->AcademicCalender;
    }

    public function setAcademicCalender(?AcademicCalender $AcademicCalender): static
    {
        $this->AcademicCalender = $AcademicCalender;

        return $this;
    }

    public function getDate(): ?\DateTimeImmutable
    {
        return $this->Date;
    }

    public function setDate(\DateTimeImmutable $Date): static
    {
        $this->Date = $Date;

        return $this;
    }

    public function getPercentage(): ?float
    {
        return $this->Percentage;
    }

    public function setPercentage(float $Percentage): static
    {
        $this->Percentage = $Percentage;

        return $this;
    }

    public function getTotalQuestions(): ?int
    {
        return $this->totalQuestions;
    }

    public function setTotalQuestions(int $totalQuestions): static
    {
        $this->totalQuestions = $totalQuestions;

        return $this;
    }

    public function getAnsweredQuestions(): ?int
    {
        return $this->answeredQuestions;
    }

    public function setAnsweredQuestions(int $answeredQuestions): static
    {
        $this->answeredQuestions = $answeredQuestions;

        return $this;
    }

    public function getCorrectAnswers(): ?int
    {
        return $this->correctAnswers;
    }

    public function setCorrectAnswers(int $correctAnswers): static
    {
        $this->correctAnswers = $correctAnswers;

        return $this;
    }

    public function getGrade(): ?Grade
    {
        return $this->Grade;
    }

    public function setGrade(?Grade $Grade): static
    {
        $this->Grade = $Grade;

        return $this;
    }

    public function getTheory(): ?int
    {
        return $this->Theory;
    }

    public function setTheory(?int $Theory): static
    {
        $this->Theory = $Theory;

        return $this;
    }

    public function getTotalScore(): ?int
{
    return ($this->theoryScore ?? 0) + ($this->score ?? 0);
}


}
