<?php

namespace App\Entity;

use App\Repository\PaymentsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PaymentsRepository::class)]
class Payments
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var Collection<int, Subs>
     */
    #[ORM\ManyToMany(targetEntity: Subs::class, inversedBy: 'payments')]
    private Collection $subs;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $payment_date = null;

    #[ORM\Column]
    private ?float $amount = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $payment_method = null;

    public function __construct()
    {
        $this->subs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Subs>
     */
    public function getSubs(): Collection
    {
        return $this->subs;
    }

    public function addSub(Subs $sub): static
    {
        if (!$this->subs->contains($sub)) {
            $this->subs->add($sub);
        }

        return $this;
    }

    public function removeSub(Subs $sub): static
    {
        $this->subs->removeElement($sub);

        return $this;
    }

    public function getPaymentDate(): ?\DateTimeInterface
    {
        return $this->payment_date;
    }

    public function setPaymentDate(\DateTimeInterface $payment_date): static
    {
        $this->payment_date = $payment_date;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): static
    {
        $this->amount = $amount;

        return $this;
    }

    public function getPaymentMethod(): ?string
    {
        return $this->payment_method;
    }

    public function setPaymentMethod(?string $payment_method): static
    {
        $this->payment_method = $payment_method;

        return $this;
    }
}
