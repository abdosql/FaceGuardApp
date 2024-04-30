<?php

namespace App\Entity;

use App\Repository\TimeScheduleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TimeScheduleRepository::class)]
class TimeSchedule
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToMany(targetEntity: Session::class, inversedBy: 'timeSchedules')]
    private Collection $sessions;

    #[ORM\OneToOne(inversedBy: 'timeSchedule', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Group $group_ = null;

    public function __construct()
    {
        $this->sessions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Session>
     */
    public function getSessions(): Collection
    {
        return $this->sessions;
    }

    public function addSession(Session $session): static
    {
        if (!$this->sessions->contains($session)) {
            $this->sessions->add($session);
        }

        return $this;
    }

    public function removeSession(Session $session): static
    {
        $this->sessions->removeElement($session);

        return $this;
    }

    public function getGroup(): ?Group
    {
        return $this->group_;
    }

    public function setGroup(Group $group_): static
    {
        $this->group_ = $group_;

        return $this;
    }
}
