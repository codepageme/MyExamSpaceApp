<?php

namespace App\Entity;

use App\Repository\TermRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TermRepository::class)]
class Term
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var Collection<int, AcademicCalender>
     */
    #[ORM\OneToMany(targetEntity: AcademicCalender::class, mappedBy: 'term')]
    private Collection $academicCalenders;

    /**
     * @var Collection<int, Question>
     */
    #[ORM\OneToMany(targetEntity: Question::class, mappedBy: 'Term')]
    private Collection $questions;

    /**
     * @var Collection<int, Theory>
     */
    #[ORM\OneToMany(targetEntity: Theory::class, mappedBy: 'term', orphanRemoval: true)]
    private Collection $theories;

    public function __construct()
    {
        $this->academicCalenders = new ArrayCollection();
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

    /**
     * @return Collection<int, AcademicCalender>
     */
    public function getAcademicCalenders(): Collection
    {
        return $this->academicCalenders;
    }

    public function addAcademicCalender(AcademicCalender $academicCalender): static
    {
        if (!$this->academicCalenders->contains($academicCalender)) {
            $this->academicCalenders->add($academicCalender);
            $academicCalender->setTerm($this);
        }

        return $this;
    }

    public function removeAcademicCalender(AcademicCalender $academicCalender): static
    {
        if ($this->academicCalenders->removeElement($academicCalender)) {
            // set the owning side to null (unless already changed)
            if ($academicCalender->getTerm() === $this) {
                $academicCalender->setTerm(null);
            }
        }

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
            $question->setTerm($this);
        }

        return $this;
    }

    public function removeQuestion(Question $question): static
    {
        if ($this->questions->removeElement($question)) {
            // set the owning side to null (unless already changed)
            if ($question->getTerm() === $this) {
                $question->setTerm(null);
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
            $theory->setTerm($this);
        }

        return $this;
    }

    public function removeTheory(Theory $theory): static
    {
        if ($this->theories->removeElement($theory)) {
            // set the owning side to null (unless already changed)
            if ($theory->getTerm() === $this) {
                $theory->setTerm(null);
            }
        }

        return $this;
    }
}
