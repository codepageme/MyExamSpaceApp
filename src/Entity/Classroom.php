<?php

namespace App\Entity;

use App\Repository\ClassroomRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClassroomRepository::class)]
class Classroom
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Classname = null;

    /**
     * @var Collection<int, Subject>
     */
    #[ORM\ManyToMany(targetEntity: Subject::class, inversedBy: 'classrooms')]
    private Collection $Subject;

    #[ORM\ManyToOne(inversedBy: 'classrooms')]
    private ?Department $department = null;

    /**
     * @var Collection<int, Student>
     */
    #[ORM\OneToMany(targetEntity: Student::class, mappedBy: 'classroom')]
    private Collection $students;

    /**
     * @var Collection<int, TeacherClassroom>
     */
    #[ORM\OneToMany(targetEntity: TeacherClassroom::class, mappedBy: 'classroom', orphanRemoval: true)]
    private Collection $teacherClassrooms;

    #[ORM\ManyToMany(targetEntity: Question::class, mappedBy: 'classrooms')]
    private Collection $questions;

    /**
     * @var Collection<int, Theory>
     */
    #[ORM\ManyToMany(targetEntity: Theory::class, mappedBy: 'classrooms')]
    private Collection $theories;

    /**
     * @var Collection<int, Exam>
     */
    #[ORM\OneToMany(targetEntity: Exam::class, mappedBy: 'Classroom', orphanRemoval: true)]
    private Collection $exams;

    public function __construct()
    {
        $this->Subject = new ArrayCollection();
        $this->students = new ArrayCollection();
        $this->teacherClassrooms = new ArrayCollection();
        $this->questions = new ArrayCollection();
        $this->theories = new ArrayCollection();
        $this->exams = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClassname(): ?string
    {
        return $this->Classname;
    }

    public function setClassname(string $Classname): static
    {
        $this->Classname = $Classname;

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
    

    public function getDepartment(): ?Department
    {
        return $this->department;
    }

    public function setDepartment(?Department $department): static
    {
        $this->department = $department;

        return $this;
    }

    /**
     * @return Collection<int, Student>
     */
    public function getStudents(): Collection
    {
        return $this->students;
    }

    public function addStudent(Student $student): static
    {
        if (!$this->students->contains($student)) {
            $this->students->add($student);
            $student->setClassroom($this);
        }

        return $this;
    }

    public function removeStudent(Student $student): static
    {
        if ($this->students->removeElement($student)) {
            // set the owning side to null (unless already changed)
            if ($student->getClassroom() === $this) {
                $student->setClassroom(null);
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
            $teacherClassroom->setClassroom($this);
        }

        return $this;
    }

    public function removeTeacherClassroom(TeacherClassroom $teacherClassroom): static
    {
        if ($this->teacherClassrooms->removeElement($teacherClassroom)) {
            // set the owning side to null (unless already changed)
            if ($teacherClassroom->getClassroom() === $this) {
                $teacherClassroom->setClassroom(null);
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
            $question->addClassroom($this);
        }

        return $this;
    }

    public function removeQuestion(Question $question): static
    {
        if ($this->questions->removeElement($question)) {
            $question->removeClassroom($this);
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
            $theory->addClassroom($this);
        }

        return $this;
    }

    public function removeTheory(Theory $theory): static
    {
        if ($this->theories->removeElement($theory)) {
            $theory->removeClassroom($this);
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
            $exam->setClassroom($this);
        }

        return $this;
    }

    public function removeExam(Exam $exam): static
    {
        if ($this->exams->removeElement($exam)) {
            // set the owning side to null (unless already changed)
            if ($exam->getClassroom() === $this) {
                $exam->setClassroom(null);
            }
        }

        return $this;
    }

}
