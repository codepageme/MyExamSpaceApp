<?php

namespace App\Entity;

use App\Repository\QuestionTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuestionTypeRepository::class)]
class QuestionType
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    /**
     * @var Collection<int, Question>
     */
    #[ORM\OneToMany(targetEntity: Question::class, mappedBy: 'questionType', orphanRemoval: true)]
    private Collection $questions;

    /**
     * @var Collection<int, Theory>
     */
    #[ORM\OneToMany(targetEntity: Theory::class, mappedBy: 'questionType', orphanRemoval: true)]
    private Collection $theories;

    public function __construct()
    {
        $this->questions = new ArrayCollection();
        $this->theories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, Question>
     */
    public function getQuestions(): Collection
    {
        return $this->questions;
    }

    public function addQuestion(Question $question): static
    {
        if (!$this->questions->contains($question)) {
            $this->questions->add($question);
            $question->setQuestiontype($this);
        }

        return $this;
    }

    public function removeQuestion(Question $question): static
    {
        if ($this->questions->removeElement($question)) {
            // set the owning side to null (unless already changed)
            if ($question->getQuestiontype() === $this) {
                $question->setQuestiontype(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Theory>
     */
    public function getTheories(): Collection
    {
        return $this->theories;
    }

    public function addTheory(Theory $theory): static
    {
        if (!$this->theories->contains($theory)) {
            $this->theories->add($theory);
            $theory->setQuestionType($this);
        }

        return $this;
    }

    public function removeTheory(Theory $theory): static
    {
        if ($this->theories->removeElement($theory)) {
            // set the owning side to null (unless already changed)
            if ($theory->getQuestionType() === $this) {
                $theory->setQuestionType(null);
            }
        }

        return $this;
    }
}
