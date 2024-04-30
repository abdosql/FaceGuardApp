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

    #[ORM\Column]
    private ?int $classroom_number = null;

    #[ORM\OneToOne(mappedBy: 'classroom', cascade: ['persist', 'remove'])]
    private ?Session $session = null;

    #[ORM\Column]
    private ?bool $available = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClassroomNumber(): ?int
    {
        return $this->classroom_number;
    }

    public function setClassroomNumber(int $classroom_number): static
    {
        $this->classroom_number = $classroom_number;

        return $this;
    }

    public function getSession(): ?Session
    {
        return $this->session;
    }

    public function setSession(Session $session): static
    {
        // set the owning side of the relation if necessary
        if ($session->getClassroom() !== $this) {
            $session->setClassroom($this);
        }

        $this->session = $session;

        return $this;
    }

    public function isAvailable(): ?bool
    {
        return $this->available;
    }

    public function setAvailable(bool $available): static
    {
        $this->available = $available;

        return $this;
    }

}
