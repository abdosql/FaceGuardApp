<?php

namespace App\Entity;

use App\Repository\FacialRecognitionLogRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FacialRecognitionLogRepository::class)]
class FacialRecognitionLog
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(mappedBy: 'facialRecognition', cascade: ['persist', 'remove'])]
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
            $this->student->setFacialRecognition(null);
        }

        // set the owning side of the relation if necessary
        if ($student !== null && $student->getFacialRecognition() !== $this) {
            $student->setFacialRecognition($this);
        }

        $this->student = $student;

        return $this;
    }
}
