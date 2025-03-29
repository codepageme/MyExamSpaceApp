<?php

namespace App\Entity;

use App\Repository\ResultsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\EntityManagerInterface;

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

    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: 2, options: ["default" => 0])]
    private ?string $Score = "0.00";

    #[ORM\ManyToOne(inversedBy: 'results')]
    #[ORM\JoinColumn(nullable: false)]
    private ?AcademicCalender $AcademicCalender = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $Date = null;

    #[ORM\Column(options: ["default" => 0])]
    private ?float $Percentage = 0.0;

    #[ORM\Column(options: ["default" => 0])]
    private ?int $totalQuestions = 0;

    #[ORM\Column(options: ["default" => 0])]
    private ?int $answeredQuestions = 0;

    #[ORM\Column(options: ["default" => 0])]
    private ?int $correctAnswers = 0;

    #[ORM\ManyToOne(inversedBy: 'results')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Grade $Grade = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: 2, nullable: true, options: ["default" => 0])]
    private ?string $Theory = "0.00";

    // 🔥 Constructor to set default values
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->Date = new \DateTimeImmutable();

        // Fetch the latest active academic calendar
        $currentCalendar = $entityManager->getRepository(AcademicCalender::class)->findOneBy([]);
        
        if ($currentCalendar) {
            $this->AcademicCalender = $currentCalendar;
        }
    }

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

    public function getScore(): ?float
    {
        return $this->Score !== null ? (float) $this->Score : 0.0;
    }

    public function setScore(float $Score): static
    {
        $this->Score = number_format($Score, 2, '.', '');
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

    public function getTheory(): ?float
    {
        return $this->Theory !== null ? (float) $this->Theory : 0.0;
    }

    public function setTheory(?float $Theory): static
    {
        $this->Theory = $Theory !== null ? number_format($Theory, 2, '.', '') : "0.00";
        return $this;
    }

    public function getTotalScore(): ?float
    {
        return ($this->getTheory() ?? 0) + ($this->getScore() ?? 0);
    }
}
