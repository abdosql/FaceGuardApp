<?php

namespace App\Entity;

use App\Repository\StudentRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StudentRepository::class)]
class Student extends User
{
    #[ORM\OneToOne(targetEntity: self::class, inversedBy: 'student', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?self $rfidCard = null;

    #[ORM\OneToOne(targetEntity: self::class, mappedBy: 'rfidCard', cascade: ['persist', 'remove'])]
    private ?self $student = null;

    #[ORM\OneToOne(targetEntity: self::class, inversedBy: 'student', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?self $facialRecognition = null;

    #[ORM\ManyToOne(inversedBy: 'students')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Group $group_ = null;

    #[ORM\ManyToOne(inversedBy: 'students')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Level $Level = null;

    public function getRfidCard(): ?self
    {
        return $this->rfidCard;
    }

    public function setRfidCard(self $rfidCard): static
    {
        $this->rfidCard = $rfidCard;

        return $this;
    }

    public function getStudent(): ?self
    {
        return $this->student;
    }

    public function setStudent(self $student): static
    {
        // set the owning side of the relation if necessary
        if ($student->getRfidCard() !== $this) {
            $student->setRfidCard($this);
        }

        $this->student = $student;

        return $this;
    }

    public function getFacialRecognition(): ?self
    {
        return $this->facialRecognition;
    }

    public function setFacialRecognition(self $facialRecognition): static
    {
        $this->facialRecognition = $facialRecognition;

        return $this;
    }

    public function getGroup(): ?Group
    {
        return $this->group_;
    }

    public function setGroup(?Group $group_): static
    {
        $this->group_ = $group_;

        return $this;
    }

    public function getLevel(): ?Level
    {
        return $this->Level;
    }

    public function setLevel(?Level $Level): static
    {
        $this->Level = $Level;

        return $this;
    }
}
