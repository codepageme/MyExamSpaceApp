<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: "App\Repository\ExamScoreRepository")]
#[ORM\Table(name: "exam_scores")]
class ExamScore
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: "App\Entity\ExamResult", inversedBy: "examScores")]
    #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
    private ?ExamResult $examResult = null;

    #[ORM\ManyToOne(targetEntity: "App\Entity\ExamType")]
    #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
    private ?ExamType $examType = null;

    #[ORM\Column(type: "decimal", precision: 5, scale: 2, options: ["default" => '0.00'])]
    private string $score = '0.00';

    public function __construct(ExamResult $examResult, ExamType $examType, float $score)
    {
        $this->examResult = $examResult;
        $this->examType = $examType;
        $this->score = $score;
    }

    public function getExamResult(): ?ExamResult
    {
        return $this->examResult;
    }

    public function setExamResult(?ExamResult $examResult): self
    {
        $this->examResult = $examResult;
        return $this;
    }

    public function getExamType(): ?ExamType
    {
        return $this->examType;
    }

    public function getScore(): string
    {
        return $this->score;
    }

    // ✅ Fix: Add setScore() method
    public function setScore(float $score): self
    {
        $this->score = $score;
        return $this;
    }
}
