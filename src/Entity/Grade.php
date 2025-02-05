<?php

namespace App\Entity;

use App\Repository\GradeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GradeRepository::class)]
class Grade
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 200)]
    private ?string $grade = null;

    #[ORM\Column]
    private ?float $minPercentage = null;

    #[ORM\Column]
    private ?float $maxPercentage = null;

    /**
     * @var Collection<int, Results>
     */
    #[ORM\OneToMany(targetEntity: Results::class, mappedBy: 'Grade', orphanRemoval: true)]
    private Collection $results;

    public function __construct()
    {
        $this->results = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGrade(): ?string
    {
        return $this->grade;
    }

    public function setGrade(string $grade): static
    {
        $this->grade = $grade;

        return $this;
    }

    public function getMinPercentage(): ?float
    {
        return $this->minPercentage;
    }

    public function setMinPercentage(float $minPercentage): static
    {
        $this->minPercentage = $minPercentage;

        return $this;
    }

    public function getMaxPercentage(): ?float
    {
        return $this->maxPercentage;
    }

    public function setMaxPercentage(float $maxPercentage): static
    {
        $this->maxPercentage = $maxPercentage;

        return $this;
    }

    /**
     * @return Collection<int, Results>
     */
    public function getResults(): Collection
    {
        return $this->results;
    }

    public function addResult(Results $result): static
    {
        if (!$this->results->contains($result)) {
            $this->results->add($result);
            $result->setGrade($this);
        }

        return $this;
    }

    public function removeResult(Results $result): static
    {
        if ($this->results->removeElement($result)) {
            // set the owning side to null (unless already changed)
            if ($result->getGrade() === $this) {
                $result->setGrade(null);
            }
        }

        return $this;
    }

  
}
