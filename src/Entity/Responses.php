<?php

namespace App\Entity;

use App\Repository\ResponsesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ResponsesRepository::class)]
class Responses
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'responses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Student $Student = null;

    #[ORM\ManyToOne(inversedBy: 'responses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Question $Question = null;

    #[ORM\Column(length: 255)]
    private ?string $Response = null;

    #[ORM\Column]
    private ?bool $iscorrect = null;

    #[ORM\ManyToOne(inversedBy: 'responses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Exam $Exam = null;

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

    public function getQuestion(): ?Question
    {
        return $this->Question;
    }

    public function setQuestion(?Question $Question): static
    {
        $this->Question = $Question;

        return $this;
    }

    public function getResponse(): ?string
    {
        return $this->Response;
    }

    public function setResponse(string $Response): static
    {
        $this->Response = $Response;

        return $this;
    }

    public function iscorrect(): ?bool
    {
        return $this->iscorrect;
    }

    public function setCorrect(bool $iscorrect): static
    {
        $this->iscorrect = $iscorrect;

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
}
