<?php

namespace App\Entity;

use App\Repository\StudentRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StudentRepository::class)]
class Student
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'student', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?RFIDCard $rfid_card = null;

    #[ORM\OneToOne(inversedBy: 'student', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?FacialRecognitionLog $facialRecognition = null;

    #[ORM\ManyToOne(inversedBy: 'students')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Group $grou_p = null;

    #[ORM\ManyToOne(inversedBy: 'students')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Level $level = null;

    #[ORM\OneToOne(mappedBy: 'student', cascade: ['persist', 'remove'])]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRfidCard(): ?RFIDCard
    {
        return $this->rfid_card;
    }

    public function setRfidCard(RFIDCard $rfid_card): static
    {
        $this->rfid_card = $rfid_card;

        return $this;
    }

    public function getFacialRecognition(): ?FacialRecognitionLog
    {
        return $this->facialRecognition;
    }

    public function setFacialRecognition(FacialRecognitionLog $facialRecognition): static
    {
        $this->facialRecognition = $facialRecognition;

        return $this;
    }

    public function getGrouP(): ?Group
    {
        return $this->grou_p;
    }

    public function setGrouP(?Group $grou_p): static
    {
        $this->grou_p = $grou_p;

        return $this;
    }

    public function getLevel(): ?Level
    {
        return $this->level;
    }

    public function setLevel(?Level $level): static
    {
        $this->level = $level;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        // unset the owning side of the relation if necessary
        if ($user === null && $this->user !== null) {
            $this->user->setStudent(null);
        }

        // set the owning side of the relation if necessary
        if ($user !== null && $user->getStudent() !== $this) {
            $user->setStudent($this);
        }

        $this->user = $user;

        return $this;
    }
}
