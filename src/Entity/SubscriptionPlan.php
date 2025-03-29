<?php

namespace App\Entity;

use App\Repository\SubscriptionPlanRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SubscriptionPlanRepository::class)]
class SubscriptionPlan
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 700, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private ?string $price = null;
    #[ORM\Column(length: 255)]
    private ?string $billing_cycle = null;

    #[ORM\OneToOne(mappedBy: 'plan', cascade: ['persist', 'remove'])]
    private ?Subs $subs = null;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getBillingCycle(): ?string
    {
        return $this->billing_cycle;
    }

    public function setBillingCycle(string $billing_cycle): static
    {
        $this->billing_cycle = $billing_cycle;

        return $this;
    }

    public function getSubs(): ?Subs
    {
        return $this->subs;
    }

    public function setSubs(Subs $subs): static
    {
        // set the owning side of the relation if necessary
        if ($subs->getPlan() !== $this) {
            $subs->setPlan($this);
        }

        $this->subs = $subs;

        return $this;
    }
}
