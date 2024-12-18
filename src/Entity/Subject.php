<?php

namespace App\Entity;

use App\Repository\SubjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SubjectRepository::class)]
class Subject
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Course = null;

    /**
     * @var Collection<int, Classroom>
     */
    #[ORM\ManyToMany(targetEntity: Classroom::class, mappedBy: 'Subject')]
    private Collection $classrooms;

    /**
     * @var Collection<int, TeacherSubject>
     */
    #[ORM\OneToMany(targetEntity: TeacherSubject::class, mappedBy: 'subject', orphanRemoval: true)]
    private Collection $teacherSubjects;

    /**
     * @var Collection<int, Question>
     */
    #[ORM\OneToMany(targetEntity: Question::class, mappedBy: 'Subject')]
    private Collection $questions;

    /**
     * @var Collection<int, Theory>
     */
    #[ORM\OneToMany(targetEntity: Theory::class, mappedBy: 'subject', orphanRemoval: true)]
    private Collection $theories;

    /**
     * @var Collection<int, Exam>
     */
    #[ORM\OneToMany(targetEntity: Exam::class, mappedBy: 'Subject', orphanRemoval: true)]
    private Collection $exams;

   

    public function __construct()
    {
        $this->classrooms = new ArrayCollection();
        $this->teacherSubjects = new ArrayCollection();
        $this->questions = new ArrayCollection();
        $this->theories = new ArrayCollection();
        $this->exams = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCourse(): ?string
    {
        return $this->Course;
    }

    public function setCourse(string $Course): static
    {
        $this->Course = $Course;

        return $this;
    }

    /**
     * @return Collection<int, Classroom>
     */
    public function getClassrooms(): Collection
    {
        return $this->classrooms;
    }

    public function addClassroom(Classroom $classroom): static
    {
        if (!$this->classrooms->contains($classroom)) {
            $this->classrooms->add($classroom);
            $classroom->addSubject($this);
        }

        return $this;
    }

    public function removeClassroom(Classroom $classroom): static
    {
        if ($this->classrooms->removeElement($classroom)) {
            $classroom->removeSubject($this);
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
            $teacherSubject->setSubject($this);
        }

        return $this;
    }

    public function removeTeacherSubject(TeacherSubject $teacherSubject): static
    {
        if ($this->teacherSubjects->removeElement($teacherSubject)) {
            // set the owning side to null (unless already changed)
            if ($teacherSubject->getSubject() === $this) {
                $teacherSubject->setSubject(null);
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
            $question->setSubject($this);
        }

        return $this;
    }

    public function removeQuestion(Question $question): static
    {
        if ($this->questions->removeElement($question)) {
            // set the owning side to null (unless already changed)
            if ($question->getSubject() === $this) {
                $question->setSubject(null);
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
            $theory->setSubject($this);
        }

        return $this;
    }

    public function removeTheory(Theory $theory): static
    {
        if ($this->theories->removeElement($theory)) {
            // set the owning side to null (unless already changed)
            if ($theory->getSubject() === $this) {
                $theory->setSubject(null);
            }
        }

        return $this;
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
            $exam->setSubject($this);
        }

        return $this;
    }

    public function removeExam(Exam $exam): static
    {
        if ($this->exams->removeElement($exam)) {
            // set the owning side to null (unless already changed)
            if ($exam->getSubject() === $this) {
                $exam->setSubject(null);
            }
        }

        return $this;
    }

}
