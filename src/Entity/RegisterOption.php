<?php

namespace App\Entity;

use App\Repository\RegisterOptionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RegisterOptionRepository::class)]
class RegisterOption
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'registerOption', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Question $Question = null;

    #[ORM\Column]
    private array $correctAnswers = [];

    #[ORM\Column]
    private array $options = [];

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

    public function getCorrectAnswers(): array
    {
        return $this->correctAnswers;
    }

    public function setCorrectAnswers(array $correctAnswers): static
    {
        $this->correctAnswers = $correctAnswers;

        return $this;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function setOptions(array $options): static
    {
        $this->options = $options;

        return $this;
    }
}
