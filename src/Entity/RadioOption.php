<?php

namespace App\Entity;

use App\Repository\RadioOptionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RadioOptionRepository::class)]
class RadioOption
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'radioOption', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Question $Question = null;

    #[ORM\Column(length: 255)]
    private ?string $optionA = null;

    #[ORM\Column(length: 255)]
    private ?string $optionB = null;

    #[ORM\Column(length: 255)]
    private ?string $optionC = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $optionD = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $optionE = null;

    #[ORM\Column(length: 255)]
    private ?string $correctAnswer = null;

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

    public function getOptionA(): ?string
    {
        return $this->optionA;
    }

    public function setOptionA(string $optionA): static
    {
        $this->optionA = $optionA;

        return $this;
    }

    public function getOptionB(): ?string
    {
        return $this->optionB;
    }

    public function setOptionB(string $optionB): static
    {
        $this->optionB = $optionB;

        return $this;
    }

    public function getOptionC(): ?string
    {
        return $this->optionC;
    }

    public function setOptionC(string $optionC): static
    {
        $this->optionC = $optionC;

        return $this;
    }

    public function getOptionD(): ?string
    {
        return $this->optionD;
    }

    public function setOptionD(?string $optionD): static
    {
        $this->optionD = $optionD;

        return $this;
    }

    public function getOptionE(): ?string
    {
        return $this->optionE;
    }

    public function setOptionE(?string $optionE): static
    {
        $this->optionE = $optionE;

        return $this;
    }

    public function getCorrectAnswer(): ?string
    {
        return $this->correctAnswer;
    }

    public function setCorrectAnswer(string $correctAnswer): static
    {
        $this->correctAnswer = $correctAnswer;

        return $this;
    }
}
