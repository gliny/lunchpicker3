<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\LunchRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: LunchRepository::class)]
#[ApiResource]
class Lunch
{
    #[Groups('read')]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups('read')]
    #[ORM\Column]
    private ?bool $selectable = null;

    #[ORM\ManyToOne(inversedBy: 'lunches')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Day $day = null;

    #[Groups('read')]
    #[ORM\Column(nullable: true)]
    private ?int $number = null;

    #[Groups('read')]
    #[ORM\Column(length: 500)]
    private ?string $text = null;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'lunches')]
    private Collection $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isSelectable(): ?bool
    {
        return $this->selectable;
    }

    public function setSelectable(bool $selectable): self
    {
        $this->selectable = $selectable;

        return $this;
    }

    public function getDay(): ?Day
    {
        return $this->day;
    }

    public function setDay(?Day $day): self
    {
        $this->day = $day;

        return $this;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(?int $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->addLunch($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            $user->removeLunch($this);
        }

        return $this;
    }
}
