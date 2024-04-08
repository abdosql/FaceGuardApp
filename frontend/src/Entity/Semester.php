<?php

namespace App\Entity;

use App\Repository\SemestreRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SemestreRepository::class)]
class Semester
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $semester_name = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSemesterName(): ?string
    {
        return $this->semester_name;
    }

    public function setSemesterName(string $semester_name): static
    {
        $this->semester_name = $semester_name;

        return $this;
    }
}
