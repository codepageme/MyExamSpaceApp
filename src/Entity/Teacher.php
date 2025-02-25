<?php 

namespace App\Entity;

use App\Repository\TeacherRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: TeacherRepository::class)]
class Teacher implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $prefix = null;

    #[ORM\Column(length: 255)]
    private ?string $firstname = null;

    #[ORM\Column(length: 255)]
    private ?string $lastname = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $username = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column(length: 250)]
    private ?string $number = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(type: 'json')]
    private array $roles = [];

    #[ORM\OneToMany(targetEntity: TeacherNote::class, mappedBy: 'teacher')]
    private Collection $teacherNotes;

    /**
     * @var Collection<int, Question>
     */
    #[ORM\OneToMany(targetEntity: Question::class, mappedBy: 'teacher')]
    private Collection $questions;

    /**
     * @var Collection<int, TeacherSubject>
     */
    #[ORM\OneToMany(targetEntity: TeacherSubject::class, mappedBy: 'teacher')]
    private Collection $teacherSubjects;

    /**
     * @var Collection<int, TeacherClassroom>
     */
    #[ORM\OneToMany(targetEntity: TeacherClassroom::class, mappedBy: 'teacher', orphanRemoval: true)]
    private Collection $teacherClassrooms;

    /**
     * @var Collection<int, Theory>
     */
    #[ORM\OneToMany(targetEntity: Theory::class, mappedBy: 'Teacher', orphanRemoval: true)]
    private Collection $theories;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $profilePicture = null;




    public function __construct()
    {
        $this->teacherNotes = new ArrayCollection();
        $this->questions = new ArrayCollection();
        $this->teacherSubjects = new ArrayCollection();
        $this->teacherClassrooms = new ArrayCollection();
        $this->theories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrefix(): ?string
    {
        return $this->prefix;
    }

    public function setPrefix(string $prefix): static
    {
        $this->prefix = $prefix;
        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;
        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;
        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;
        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;
        return $this;
    }

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(string $number): static
    {
        $this->number = $number;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;
        return $this;
    }

    public function getRoles(): array
    {
        // Guarantee that the user always has at least one role
        $roles = $this->roles;
        $roles[] = 'ROLE_TEACHER';
        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;
        return $this;
    }

    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
    }


    
    // Teacher note reference begins here 
    /**
     * @return Collection<int, TeacherNote>
     */
    public function getTeacherNotes(): Collection
    {
        return $this->teacherNotes;
    }

    public function addTeacherNote(TeacherNote $teacherNote): self
    {
        if (!$this->teacherNotes->contains($teacherNote)) {
            $this->teacherNotes->add($teacherNote);
            $teacherNote->setTeacher($this);
        }

        return $this;
    }

    public function removeTeacherNote(TeacherNote $teacherNote): self
    {
        if ($this->teacherNotes->removeElement($teacherNote)) {
            // Set the owning side to null (unless already changed)
            if ($teacherNote->getTeacher() === $this) {
                $teacherNote->setTeacher(null);
            }
        }

        return $this;
    }

    // Implementing UserInterface method
    public function getUserIdentifier(): string
    {
        return $this->username; // or return $this->email if that's your identifier
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
            $question->setTeacher($this);
        }

        return $this;
    }

    public function removeQuestion(Question $question): static
    {
        if ($this->questions->removeElement($question)) {
            // set the owning side to null (unless already changed)
            if ($question->getTeacher() === $this) {
                $question->setTeacher(null);
            }
        }

        return $this;
    }



    /**
     * @return Collection<int, TeacherSubject>
     */
    public function getTeacherSubjects(): Collection
    {
        return $this->teacherSubjects;
    }

    public function addTeacherSubject(TeacherSubject $teacherSubject): static
    {
        if (!$this->teacherSubjects->contains($teacherSubject)) {
            $this->teacherSubjects->add($teacherSubject);
            $teacherSubject->setTeacher($this);
        }

        return $this;
    }

    public function removeTeacherSubject(TeacherSubject $teacherSubject): static
    {
        if ($this->teacherSubjects->removeElement($teacherSubject)) {
            // set the owning side to null (unless already changed)
            if ($teacherSubject->getTeacher() === $this) {
                $teacherSubject->setTeacher(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, TeacherClassroom>
     */
    public function getTeacherClassrooms(): Collection
    {
        return $this->teacherClassrooms;
    }

    public function addTeacherClassroom(TeacherClassroom $teacherClassroom): static
    {
        if (!$this->teacherClassrooms->contains($teacherClassroom)) {
            $this->teacherClassrooms->add($teacherClassroom);
            $teacherClassroom->setTeacher($this);
        }

        return $this;
    }

    public function removeTeacherClassroom(TeacherClassroom $teacherClassroom): static
    {
        if ($this->teacherClassrooms->removeElement($teacherClassroom)) {
            // set the owning side to null (unless already changed)
            if ($teacherClassroom->getTeacher() === $this) {
                $teacherClassroom->setTeacher(null);
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
            $theory->setTeacher($this);
        }

        return $this;
    }

    public function removeTheory(Theory $theory): static
    {
        if ($this->theories->removeElement($theory)) {
            // set the owning side to null (unless already changed)
            if ($theory->getTeacher() === $this) {
                $theory->setTeacher(null);
            }
        }

        return $this;
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

   
   
}
