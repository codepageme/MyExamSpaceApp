<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: "App\Repository\ExamResultRepository")]
#[ORM\Table(name: "exam_results")]
class ExamResult
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: "App\Entity\Session")]
    #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
    private ?Session $session = null;

    #[ORM\ManyToOne(targetEntity: "App\Entity\Term")]
    #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
    private ?Term $term = null;

    #[ORM\ManyToOne(targetEntity: "App\Entity\Classroom")]
    #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
    private ?Classroom $classroom = null;

    #[ORM\ManyToOne(targetEntity: "App\Entity\Subject")]
    #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
    private ?Subject $subject = null;

    #[ORM\ManyToOne(targetEntity: "App\Entity\Student")]
    #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
    private ?Student $student = null;

    #[ORM\OneToMany(targetEntity: "App\Entity\ExamScore", mappedBy: "examResult", cascade: ["persist", "remove"])]
    private Collection $examScores;

    #[ORM\Column(type: "decimal", precision: 5, scale: 2, options: ["default" => '0.00'])]
    private string $testScore = '0.00'; // Test score (out of 40)

    #[ORM\Column(type: "decimal", precision: 5, scale: 2, options: ["default" => '0.00'])]
    private string $examScore = '0.00'; // Exam score (out of 60)

    #[ORM\Column(type: "datetime", options: ["default" => "CURRENT_TIMESTAMP"])]
    private \DateTime $createdAt;

    #[ORM\Column(type: "datetime", options: ["default" => "CURRENT_TIMESTAMP", "onUpdate" => "CURRENT_TIMESTAMP"])]
    private \DateTime $updatedAt;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
        $this->examScores = new ArrayCollection();
    }

    // 🚀 Dynamically Compute Total Score
    public function getTotalScore(): float
    {
        return $this->testScore + $this->examScore;
    }

    // ✅ Add Exam Score to Collection
    public function addExamScore(ExamScore $examScore): self
    {
        if (!$this->examScores->contains($examScore)) {
            $this->examScores[] = $examScore;
            $examScore->setExamResult($this);
        }

        return $this;
    }

    // 🆔 Get ID
    public function getId(): ?int
    {
        return $this->id;
    }

    // 📅 Get & Set Session
    public function getSession(): ?Session
    {
        return $this->session;
    }

    public function setSession(Session $session): self
    {
        $this->session = $session;
        return $this;
    }

    // 🔄 Get & Set Term
    public function getTerm(): ?Term
    {
        return $this->term;
    }

    public function setTerm(Term $term): self
    {
        $this->term = $term;
        return $this;
    }

    // 🏫 Get & Set Classroom
    public function getClassroom(): ?Classroom
    {
        return $this->classroom;
    }

    public function setClassroom(Classroom $classroom): self
    {
        $this->classroom = $classroom;
        return $this;
    }

    // 📚 Get & Set Subject
    public function getSubject(): ?Subject
    {
        return $this->subject;
    }

    public function setSubject(Subject $subject): self
    {
        $this->subject = $subject;
        return $this;
    }

    // 🧑‍🎓 Get & Set Student
    public function getStudent(): ?Student
    {
        return $this->student;
    }

    public function setStudent(Student $student): self
    {
        $this->student = $student;
        return $this;
    }

    // 📝 Get & Set Test Score
    public function getTestScore(): string
    {
        return $this->testScore;
    }

    public function setTestScore(float $testScore): self
    {
        $this->testScore = min(40, $testScore);
        return $this;
    }

    // 📝 Get & Set Exam Score
    public function getExamScore(): string
    {
        return $this->examScore;
    }

    public function setExamScore(float $examScore): self
    {
        $this->examScore = min(60, $examScore);
        return $this;
    }

    // 📅 Get & Set Created At
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    // 🔄 Get & Set Updated At
    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTime $updatedAt): self
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }
}
