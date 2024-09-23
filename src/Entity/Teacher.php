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
     * @var Collection<int, Exam>
     */
    #[ORM\OneToMany(targetEntity: Exam::class, mappedBy: 'teacher')]
    private Collection $exams;

    /**
     * @var Collection<int, Question>
     */
    #[ORM\OneToMany(targetEntity: Question::class, mappedBy: 'teacher')]
    private Collection $questions;

    /**
     * @var Collection<int, Subject>
     */
    #[ORM\ManyToMany(targetEntity: Subject::class, mappedBy: 'teacher')]
    private Collection $subjects;

    /**
     * @var Collection<int, Subject>
     */
    #[ORM\ManyToMany(targetEntity: Subject::class, inversedBy: 'teachers')]
    private Collection $Subject;

    /**
     * @var Collection<int, Classroom>
     */
    #[ORM\ManyToMany(targetEntity: Classroom::class, inversedBy: 'teachers')]
    private Collection $Classroom;


    public function __construct()
    {
        $this->teacherNotes = new ArrayCollection();
        $this->exams = new ArrayCollection();
        $this->questions = new ArrayCollection();
        $this->subjects = new ArrayCollection();
        $this->Subject = new ArrayCollection();
        $this->Classroom = new ArrayCollection();
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
     * @return Collection<int, Exam>
     */
    public function getExams(): Collection
    {
        return $this->exams;
    }

    public function addExam(Exam $exam): static
    {
        if (!$this->exams->contains($exam)) {
            $this->exams->add($exam);
            $exam->setTeacher($this);
        }

        return $this;
    }

    public function removeExam(Exam $exam): static
    {
        if ($this->exams->removeElement($exam)) {
            // set the owning side to null (unless already changed)
            if ($exam->getTeacher() === $this) {
                $exam->setTeacher(null);
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
     * @return Collection<int, Subject>
     */
    public function getSubjects(): Collection
    {
        return $this->subjects;
    }

    public function addSubjects(Subject $subjects): static
    {
        if (!$this->subjects->contains($subjects)) {
            $this->subjects->add($subjects);
            $subjects->addTeacher($this);
        }

        return $this;
    }

    public function removeSubjectss(Subject $subjects): static
    {
        if ($this->subjects->removeElement($subjects)) {
            $subjects->removeTeacher($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Subject>
     */
    public function getSubject(): Collection
    {
        return $this->Subject;
    }

    public function addSubject(Subject $subject): static
    {
        if (!$this->Subject->contains($subject)) {
            $this->Subject->add($subject);
        }

        return $this;
    }

    public function removeSubject(Subject $subject): static
    {
        $this->Subject->removeElement($subject);

        return $this;
    }

    /**
     * @return Collection<int, Classroom>
     */
    public function getClassroom(): Collection
    {
        return $this->Classroom;
    }

    public function addClassroom(Classroom $classroom): static
    {
        if (!$this->Classroom->contains($classroom)) {
            $this->Classroom->add($classroom);
        }

        return $this;
    }

    public function removeClassroom(Classroom $classroom): static
    {
        $this->Classroom->removeElement($classroom);

        return $this;
    }

   
}
