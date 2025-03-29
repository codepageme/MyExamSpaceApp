<?php

namespace App\Entity;

use App\Repository\ExamConfigurationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ExamConfigurationRepository::class)]
class ExamConfiguration  
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $maxExamScore = null;

    #[ORM\Column]
    private ?int $maxTestScore = null;

    #[ORM\Column]
    private ?int $scaledExamScore = 60; // Default: 60

    #[ORM\Column]
    private ?int $scaledTestScore = 40; // Default: 40

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMaxExamScore(): ?int
    {
        return $this->maxExamScore;
    }

    public function setMaxExamScore(int $maxExamScore): static
    {
        $this->maxExamScore = $maxExamScore;
        return $this;
    }

    public function getMaxTestScore(): ?int
    {
        return $this->maxTestScore;
    }

    public function setMaxTestScore(int $maxTestScore): static
    {
        $this->maxTestScore = $maxTestScore;
        return $this;
    }

    public function getScaledExamScore(): ?int
    {
        return $this->scaledExamScore;
    }

    public function setScaledExamScore(int $scaledExamScore): static
    {
        $this->scaledExamScore = $scaledExamScore;
        return $this;
    }

    public function getScaledTestScore(): ?int
    {
        return $this->scaledTestScore;
    }

    public function setScaledTestScore(int $scaledTestScore): static
    {
        $this->scaledTestScore = $scaledTestScore;
        return $this;
    }
}
