<?php

namespace App\Entity;

use App\Repository\StudentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StudentRepository::class)]
class Student
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Firstname = null;

    #[ORM\Column(length: 255)]
    private ?string $Lastname = null;

    #[ORM\Column(length: 255)]
    private ?string $Middlename = null;

    #[ORM\ManyToOne(inversedBy: 'students')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Classroom $classroom = null;


    #[ORM\Column(length: 255)]
    private ?string $Gender = null;

    #[ORM\Column(type: Types::JSON)]
    private array $Roles = [];

    #[ORM\Column]
    private ?int $admissionYear = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateOfBirth = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $profilePicture = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Email = null;

    /**
     * @var Collection<int, Responses>
     */
    #[ORM\OneToMany(targetEntity: Responses::class, mappedBy: 'Student', orphanRemoval: true)]
    private Collection $responses;

    /**
     * @var Collection<int, Results>
     */
    #[ORM\OneToMany(targetEntity: Results::class, mappedBy: 'Student')]
    private Collection $results;

    public function __construct()
    {
        $this->responses = new ArrayCollection();
        $this->results = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->Firstname;
    }

    public function setFirstname(string $Firstname): static
    {
        $this->Firstname = $Firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->Lastname;
    }

    public function setLastname(string $Lastname): static
    {
        $this->Lastname = $Lastname;

        return $this;
    }

    public function getMiddlename(): ?string
    {
        return $this->Middlename;
    }

    public function setMiddlename(string $Middlename): static
    {
        $this->Middlename = $Middlename;

        return $this;
    }

    public function getClassroom(): ?Classroom
    {
        return $this->classroom;
    }

    public function setClassroom(?Classroom $classroom): static
    {
        $this->classroom = $classroom;

        return $this;
    }


    public function getGender(): ?string
    {
        return $this->Gender;
    }

    public function setGender(string $Gender): static
    {
        $this->Gender = $Gender;

        return $this;
    }

    public function getRoles(): array
    {
        return $this->Roles;
    }

    public function setRoles(array $Roles): static
    {
        $this->Roles = $Roles;

        return $this;
    }

    public function getAdmissionYear(): ?int
    {
        return $this->admissionYear;
    }

    public function setAdmissionYear(int $admissionYear): static
    {
        $this->admissionYear = $admissionYear;

        return $this;
    }

    public function getDateOfBirth(): ?\DateTimeInterface
    {
        return $this->dateOfBirth;
    }

    public function setDateOfBirth(\DateTimeInterface $dateOfBirth): static
    {
        $this->dateOfBirth = $dateOfBirth;

        return $this;
    }

    public function getAge(): int
{
    return $this->dateOfBirth ? (new \DateTime())->diff($this->dateOfBirth)->y : 0; // returns 0 if dateOfBirth is null
}

    public function getProfilePicture(): ?string
    {
        return $this->profilePicture;
    }

    public function setProfilePicture(?string $profilePicture): static
    {
        $this->profilePicture = $profilePicture;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->Email;
    }

    public function setEmail(string $Email): static
    {
        $this->Email = $Email;

        return $this;
    }

    /**
     * @return Collection<int, Responses>
     */
    public function getResponses(): Collection
    {
        return $this->responses;
    }

    public function addResponse(Responses $response): static
    {
        if (!$this->responses->contains($response)) {
            $this->responses->add($response);
            $response->setStudent($this);
        }

        return $this;
    }

    public function removeResponse(Responses $response): static
    {
        if ($this->responses->removeElement($response)) {
            // set the owning side to null (unless already changed)
            if ($response->getStudent() === $this) {
                $response->setStudent(null);
            }
        }

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
            $result->setStudent($this);
        }

        return $this;
    }

    public function removeResult(Results $result): static
    {
        if ($this->results->removeElement($result)) {
            // set the owning side to null (unless already changed)
            if ($result->getStudent() === $this) {
                $result->setStudent(null);
            }
        }

        return $this;
    }


}

