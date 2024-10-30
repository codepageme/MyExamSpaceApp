<?php

namespace App\Entity;

use App\Repository\TodoListRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TodoListRepository::class)]
class TodoList
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $task = null;

    #[ORM\Column]
    private bool $isCompleted = false; // Set a default value

    #[ORM\ManyToOne(inversedBy: 'todolists')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Admin $admin = null; // Capitalized Admin

    #[ORM\Column]
    private \DateTimeImmutable $createdAt; // Make this non-nullable

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $deadline = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $completionDate = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable(); // Initialize to current time
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTask(): ?string
    {
        return $this->task;
    }

    public function setTask(?string $task): static
    {
        $this->task = $task;

        return $this;
    }

    public function isCompleted(): bool // Return type is now bool
    {
        return $this->isCompleted;
    }

    public function setCompleted(bool $isCompleted): static
    {
        $this->isCompleted = $isCompleted;

        return $this;
    }

    public function getAdmin(): ?Admin // Capitalized Admin
    {
        return $this->admin;
    }

    public function setAdmin(?Admin $admin): static
    {
        $this->admin = $admin;

        return $this;
    }

    public function getCreatedAt(): \DateTimeImmutable // No nullable type
    {
        return $this->createdAt;
    }

    public function getDeadline(): ?\DateTimeInterface
    {
        return $this->deadline;
    }

    public function setDeadline(\DateTimeInterface $deadline): static
    {
        $this->deadline = $deadline;

        return $this;
    }

    public function getCompletionDate(): ?\DateTimeInterface
    {
        return $this->completionDate;
    }

    public function setCompletionDate(?\DateTimeInterface $completionDate): static
    {
        $this->completionDate = $completionDate;

        return $this;
    }
}
