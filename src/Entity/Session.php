<?php

namespace App\Entity;

use App\Repository\SessionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SessionRepository::class)]
class Session
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
    #[ORM\OneToMany(targetEntity: AcademicCalender::class, mappedBy: 'session')]
    private Collection $academicCalenders;

    /**
     * @var Collection<int, Question>
     */
    #[ORM\OneToMany(targetEntity: Question::class, mappedBy: 'Session')]
    private Collection $questions;

    /**
     * @var Collection<int, Theory>
     */
    #[ORM\OneToMany(targetEntity: Theory::class, mappedBy: 'session', orphanRemoval: true)]
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
            $academicCalender->setSession($this);
        }

        return $this;
    }

    public function removeAcademicCalender(AcademicCalender $academicCalender): static
    {
        if ($this->academicCalenders->removeElement($academicCalender)) {
            // set the owning side to null (unless already changed)
            if ($academicCalender->getSession() === $this) {
                $academicCalender->setSession(null);
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
            $question->setSession($this);
        }

        return $this;
    }

    public function removeQuestion(Question $question): static
    {
        if ($this->questions->removeElement($question)) {
            // set the owning side to null (unless already changed)
            if ($question->getSession() === $this) {
                $question->setSession(null);
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
            $theory->setSession($this);
        }

        return $this;
    }

    public function removeTheory(Theory $theory): static
    {
        if ($this->theories->removeElement($theory)) {
            // set the owning side to null (unless already changed)
            if ($theory->getSession() === $this) {
                $theory->setSession(null);
            }
        }

        return $this;
    }
}
