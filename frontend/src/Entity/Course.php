<?php

namespace App\Entity;

use App\Repository\CourseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CourseRepository::class)]
class Course
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $course_name = null;

    #[ORM\ManyToOne(inversedBy: 'courses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Level $levels = null;

    #[ORM\ManyToMany(targetEntity: Semestre::class, inversedBy: 'courses')]
    private Collection $semstres;

    #[ORM\ManyToOne(inversedBy: 'Courses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Teacher $teacher = null;

    public function __construct()
    {
        $this->semstres = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCourseName(): ?string
    {
        return $this->course_name;
    }

    public function setCourseName(string $course_name): static
    {
        $this->course_name = $course_name;

        return $this;
    }

    public function getLevels(): ?Level
    {
        return $this->levels;
    }

    public function setLevels(?Level $levels): static
    {
        $this->levels = $levels;

        return $this;
    }

    /**
     * @return Collection<int, Semestre>
     */
    public function getSemstres(): Collection
    {
        return $this->semstres;
    }

    public function addSemstre(Semestre $semstre): static
    {
        if (!$this->semstres->contains($semstre)) {
            $this->semstres->add($semstre);
        }

        return $this;
    }

    public function removeSemstre(Semestre $semstre): static
    {
        $this->semstres->removeElement($semstre);

        return $this;
    }

    public function getTeacher(): ?Teacher
    {
        return $this->teacher;
    }

    public function setTeacher(?Teacher $teacher): static
    {
        $this->teacher = $teacher;

        return $this;
    }
}
