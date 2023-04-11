<?php

namespace App\Entity;

use App\Repository\CommandeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\IntegerType;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommandeRepository::class)]
class Commande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'commandes')]
    private ?User $user = null;

    #[ORM\Column]
    private ?int $number = null;

    #[ORM\ManyToMany(targetEntity: Wine::class, inversedBy: 'commandes')]
    private Collection $wine;

    public function __construct()
    {
        $this->wine = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(int $number): self
    {
        $this->number = $number;

        return $this;
    }

    /**
     * @return Collection<int, Wine>
     */
    public function getWine(): Collection
    {
        return $this->wine;
    }

    public  function getTotal(): int
    {
        $total = 0;
        foreach ($this->getWine() as $wine) {
            $total += $wine->getPrice() * $this->getNumber();
        }
        return $total;
    }


    public function addWine(Wine $wine): self
    {
        if (!$this->wine->contains($wine)) {
            $this->wine->add($wine);
        }

        return $this;
    }

    public function removeWine(Wine $wine): self
    {
        $this->wine->removeElement($wine);

        return $this;
    }



}
