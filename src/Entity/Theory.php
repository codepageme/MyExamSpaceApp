<?php

namespace App\Entity;

use App\Repository\TheoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TheoryRepository::class)]
class Theory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $Question = null;

    #[ORM\ManyToOne(inversedBy: 'theories')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Subject $subject = null;

    /**
     * @var Collection<int, classroom>
     */
    #[ORM\ManyToMany(targetEntity: classroom::class, inversedBy: 'theories')]
    private Collection $classrooms;

    #[ORM\ManyToOne(inversedBy: 'theories')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Term $term = null;

    #[ORM\ManyToOne(inversedBy: 'theories')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Session $session = null;

    #[ORM\ManyToOne(inversedBy: 'theories')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Examtype $examType = null;

    #[ORM\ManyToOne(inversedBy: 'theories')]
    #[ORM\JoinColumn(nullable: false)]
    private ?QuestionType $questionType = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'theories')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Teacher $Teacher = null;

    public function __construct()
    {
        $this->classrooms = new ArrayCollection();
    }

    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuestion(): ?string
    {
        return $this->Question;
    }

    public function setQuestion(string $Question): static
    {
        $this->Question = $Question;

        return $this;
    }

    public function getSubject(): ?Subject
    {
        return $this->subject;
    }

    public function setSubject(?Subject $subject): static
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * @return Collection<int, classroom>
     */
    public function getClassrooms(): Collection
    {
        return $this->classrooms;
    }

    public function addClassroom(classroom $classroom): static
    {
        if (!$this->classrooms->contains($classroom)) {
            $this->classrooms->add($classroom);
        }

        return $this;
    }

    public function removeClassroom(classroom $classroom): static
    {
        $this->classrooms->removeElement($classroom);

        return $this;
    }

    public function getTerm(): ?Term
    {
        return $this->term;
    }

    public function setTerm(?Term $term): static
    {
        $this->term = $term;

        return $this;
    }

    public function getSession(): ?Session
    {
        return $this->session;
    }

    public function setSession(?Session $session): static
    {
        $this->session = $session;

        return $this;
    }

    public function getExamType(): ?Examtype
    {
        return $this->examType;
    }

    public function setExamType(?Examtype $examType): static
    {
        $this->examType = $examType;

        return $this;
    }

    public function getQuestionType(): ?QuestionType
    {
        return $this->questionType;
    }

    public function setQuestionType(?QuestionType $questionType): static
    {
        $this->questionType = $questionType;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getTeacher(): ?Teacher
    {
        return $this->Teacher;
    }

    public function setTeacher(?Teacher $Teacher): static
    {
        $this->Teacher = $Teacher;

        return $this;
    }

    
}
