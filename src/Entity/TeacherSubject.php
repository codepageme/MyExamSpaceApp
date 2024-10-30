<?php

namespace App\Entity;

use App\Repository\TeacherSubjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TeacherSubjectRepository::class)]
class TeacherSubject
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'teacherSubjects')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Teacher $teacher = null;

    #[ORM\ManyToOne(inversedBy: 'teacherSubjects')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Subject $subject = null;

    /**
     * @var Collection<int, TeacherSubjectClassroom>
     */
    #[ORM\OneToMany(targetEntity: TeacherSubjectClassroom::class, mappedBy: 'teacherSubject', orphanRemoval: true)]
    private Collection $teacherSubjectClassrooms;

    public function __construct()
    {
        $this->teacherSubjectClassrooms = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTeacher(): ?Teacher
    {
        return $this->teacher;
    }

    public function setTeacher(?Teacher $teacher): self
    {
        $this->teacher = $teacher;
        return $this;
    }

    public function getSubject(): ?Subject
    {
        return $this->subject;
    }

    public function setSubject(?Subject $subject): self
    {
        $this->subject = $subject;
        return $this;
    }

    /**
     * @return Collection<int, TeacherSubjectClassroom>
     */
    public function getTeacherSubjectClassrooms(): Collection
    {
        return $this->teacherSubjectClassrooms;
    }

    public function addTeacherSubjectClassroom(TeacherSubjectClassroom $teacherSubjectClassroom): self
    {
        if (!$this->teacherSubjectClassrooms->contains($teacherSubjectClassroom)) {
            $this->teacherSubjectClassrooms->add($teacherSubjectClassroom);
            $teacherSubjectClassroom->setTeacherSubject($this);
        }
        return $this;
    }

    public function removeTeacherSubjectClassroom(TeacherSubjectClassroom $teacherSubjectClassroom): self
    {
        if ($this->teacherSubjectClassrooms->removeElement($teacherSubjectClassroom)) {
            // Set the owning side to null (unless already changed)
            if ($teacherSubjectClassroom->getTeacherSubject() === $this) {
                $teacherSubjectClassroom->setTeacherSubject(null);
            }
        }
        return $this;
    }
}
