<?php

namespace App\Entity;

use App\Repository\QuestionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;


#[ORM\Entity(repositoryClass: QuestionRepository::class)]
class Question
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    #[ORM\ManyToOne(inversedBy: 'questions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?QuestionType $questionType = null; // Updated to QuestionType

    #[ORM\ManyToOne(inversedBy: 'questions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Teacher $teacher = null;

    #[ORM\ManyToOne(inversedBy: 'questions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Examtype $examtype = null;

    #[ORM\ManyToMany(targetEntity: Classroom::class, inversedBy: 'questions')]
    #[ORM\JoinTable(name: 'question_classroom')]
    private Collection $classrooms;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'questions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Subject $Subject = null;

    #[ORM\OneToOne(mappedBy: 'Question', cascade: ['persist', 'remove'])]
    private ?RadioOption $radioOption = null;

    #[ORM\ManyToOne(inversedBy: 'questions')]
    private ?Session $Session = null;

    #[ORM\ManyToOne(inversedBy: 'questions')]
    private ?Term $Term = null;

    #[ORM\OneToOne(mappedBy: 'Question', cascade: ['persist', 'remove'])]
    private ?CheckboxOption $checkboxOption = null;

    #[ORM\OneToOne(mappedBy: 'Question', cascade: ['persist', 'remove'])]
    private ?BooleanOption $booleanOption = null;

    #[ORM\OneToOne(mappedBy: 'Question', cascade: ['persist', 'remove'])]
    private ?GermanOption $germanOption = null;

    #[ORM\OneToOne(mappedBy: 'Question', cascade: ['persist', 'remove'])]
    private ?DropdownOption $dropdownOption = null;

    #[ORM\OneToOne(mappedBy: 'Question', cascade: ['persist', 'remove'])]
    private ?RegisterOption $registerOption = null;

    #[ORM\OneToOne(mappedBy: 'Question', cascade: ['persist', 'remove'])]
    private ?ImagesOption $imagesOption = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getQuestionType(): ?QuestionType // Updated method name
    {
        return $this->questionType;
    }

    public function setQuestionType(?QuestionType $questionType): static // Updated method name
    {
        $this->questionType = $questionType;

        return $this;
    }

    public function getTeacher(): ?Teacher // Updated method name
    {
        return $this->teacher;
    }

    public function setTeacher(?Teacher $teacher): static // Updated method name
    {
        $this->teacher = $teacher;

        return $this;
    }

    public function getExamtype(): ?Examtype
    {
        return $this->examtype;
    }

    public function setExamtype(?Examtype $examtype): static
    {
        $this->examtype = $examtype;

        return $this;
    }

    public function __construct()
    {
        $this->classrooms = new ArrayCollection();
    }
    
    public function getClassrooms(): Collection
    {
        return $this->classrooms;
    }
    
    public function addClassroom(Classroom $classroom): static
    {
        if (!$this->classrooms->contains($classroom)) {
            $this->classrooms->add($classroom);
        }
        return $this;
    }
    
    public function removeClassroom(Classroom $classroom): static
    {
        $this->classrooms->removeElement($classroom);
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getSubject(): ?Subject
    {
        return $this->Subject;
    }

    public function setSubject(?Subject $Subject): static
    {
        $this->Subject = $Subject;

        return $this;
    }

    public function getRadioOption(): ?RadioOption
    {
        return $this->radioOption;
    }

    public function setRadioOption(RadioOption $radioOption): static
    {
        // set the owning side of the relation if necessary
        if ($radioOption->getQuestion() !== $this) {
            $radioOption->setQuestion($this);
        }

        $this->radioOption = $radioOption;

        return $this;
    }

    public function getSession(): ?Session
    {
        return $this->Session;
    }

    public function setSession(?Session $Session): static
    {
        $this->Session = $Session;

        return $this;
    }

    public function getTerm(): ?Term
    {
        return $this->Term;
    }

    public function setTerm(?Term $Term): static
    {
        $this->Term = $Term;

        return $this;
    }

    public function getCheckboxOption(): ?CheckboxOption
{
    return $this->checkboxOption;
}

public function setCheckboxOption(?CheckboxOption $checkboxOption): static
{
    if ($checkboxOption !== null && $checkboxOption->getQuestion() !== $this) {
        $checkboxOption->setQuestion($this);
    }
    $this->checkboxOption = $checkboxOption;

    return $this;
}

public function getBooleanOption(): ?BooleanOption
{
    return $this->booleanOption;
}

public function setBooleanOption(BooleanOption $booleanOption): static
{
    // set the owning side of the relation if necessary
    if ($booleanOption->getQuestion() !== $this) {
        $booleanOption->setQuestion($this);
    }

    $this->booleanOption = $booleanOption;

    return $this;
}

public function getGermanOption(): ?GermanOption
{
    return $this->germanOption;
}

public function setGermanOption(?GermanOption $germanOption): static
{
    // unset the owning side of the relation if necessary
    if ($germanOption === null && $this->germanOption !== null) {
        $this->germanOption->setQuestion(null);
    }

    // set the owning side of the relation if necessary
    if ($germanOption !== null && $germanOption->getQuestion() !== $this) {
        $germanOption->setQuestion($this);
    }

    $this->germanOption = $germanOption;

    return $this;
}

public function getDropdownOption(): ?DropdownOption
{
    return $this->dropdownOption;
}

public function setDropdownOption(DropdownOption $dropdownOption): static
{
    // set the owning side of the relation if necessary
    if ($dropdownOption->getQuestion() !== $this) {
        $dropdownOption->setQuestion($this);
    }

    $this->dropdownOption = $dropdownOption;

    return $this;
}

public function getRegisterOption(): ?RegisterOption
{
    return $this->registerOption;
}

public function setRegisterOption(RegisterOption $registerOption): static
{
    // set the owning side of the relation if necessary
    if ($registerOption->getQuestion() !== $this) {
        $registerOption->setQuestion($this);
    }

    $this->registerOption = $registerOption;

    return $this;
}

public function getImagesOption(): ?ImagesOption
{
    return $this->imagesOption;
}

public function setImagesOption(ImagesOption $imagesOption): static
{
    // set the owning side of the relation if necessary
    if ($imagesOption->getQuestion() !== $this) {
        $imagesOption->setQuestion($this);
    }

    $this->imagesOption = $imagesOption;

    return $this;
}
   
}
