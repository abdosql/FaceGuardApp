<?php

namespace App\Entity;

use App\Repository\SemestreRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SemestreRepository::class)]
class Semestre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $semestre_name = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSemestreName(): ?string
    {
        return $this->semestre_name;
    }

    public function setSemestreName(string $semestre_name): static
    {
        $this->semestre_name = $semestre_name;

        return $this;
    }
}
