<?php

namespace App\Entity;

use App\Repository\RFIDCardRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RFIDCardRepository::class)]
class RFIDCard
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(mappedBy: 'rfidCard', cascade: ['persist', 'remove'])]
    private ?Student $student = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStudent(): ?Student
    {
        return $this->student;
    }

    public function setStudent(?Student $student): static
    {
        // unset the owning side of the relation if necessary
        if ($student === null && $this->student !== null) {
            $this->student->setRfidCard(null);
        }

        // set the owning side of the relation if necessary
        if ($student !== null && $student->getRfidCard() !== $this) {
            $student->setRfidCard($this);
        }

        $this->student = $student;

        return $this;
    }
}
