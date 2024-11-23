<?php

namespace App\Entity;

use App\Repository\BooleanOptionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BooleanOptionRepository::class)]
class BooleanOption
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'booleanOption', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Question $Question = null;

    #[ORM\Column]
    private ?bool $correctAnswer = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuestion(): ?Question
    {
        return $this->Question;
    }

    public function setQuestion(Question $Question): static
    {
        $this->Question = $Question;

        return $this;
    }

    public function isCorrectAnswer(): ?bool
    {
        return $this->correctAnswer;
    }

    public function setCorrectAnswer(bool $correctAnswer): static
    {
        $this->correctAnswer = $correctAnswer;

        return $this;
    }
}
