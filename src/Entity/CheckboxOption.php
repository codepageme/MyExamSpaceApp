<?php

namespace App\Entity;

use App\Repository\CheckboxOptionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CheckboxOptionRepository::class)]
class CheckboxOption
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'checkboxOption')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Question $Question = null;

    #[ORM\Column(length: 255)]
    private ?string $OptionA = null;

    #[ORM\Column(length: 255)]
    private ?string $OptionB = null;

    #[ORM\Column(length: 255)]
    private ?string $OptionC = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $OptionD = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $OptionE = null;

    #[ORM\Column(type: 'json')]
    private array $correctAnswers = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuestion(): ?Question
    {
        return $this->Question;
    }

    public function setQuestion(?Question $Question): static
    {
        $this->Question = $Question;

        return $this;
    }

    public function getOptionA(): ?string
    {
        return $this->OptionA;
    }

    public function setOptionA(string $OptionA): static
    {
        $this->OptionA = $OptionA;

        return $this;
    }

    public function getOptionB(): ?string
    {
        return $this->OptionB;
    }

    public function setOptionB(string $OptionB): static
    {
        $this->OptionB = $OptionB;

        return $this;
    }

    public function getOptionC(): ?string
    {
        return $this->OptionC;
    }

    public function setOptionC(string $OptionC): static
    {
        $this->OptionC = $OptionC;

        return $this;
    }

    public function getOptionD(): ?string
    {
        return $this->OptionD;
    }

    public function setOptionD(?string $OptionD): static
    {
        $this->OptionD = $OptionD;

        return $this;
    }

    public function getOptionE(): ?string
    {
        return $this->OptionE;
    }

    public function setOptionE(?string $OptionE): static
    {
        $this->OptionE = $OptionE;

        return $this;
    }

    public function getCorrectAnswers(): array
    {
        return $this->correctAnswers;
    }

    public function setCorrectAnswers(array $correctAnswers): static
    {
        $this->correctAnswers = $correctAnswers;

        return $this;
    }
}
