<?php

namespace App\Entity;

use App\Repository\CourseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
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
    private ?Teacher $teacher = null;

    #[ORM\ManyToMany(targetEntity: Branch::class, mappedBy: 'courses')]
    private Collection $branches;

    #[ORM\Column(type: Types::INTEGER)]
    private ?int $course_duration = null;

    #[ORM\OneToOne(mappedBy: 'course', cascade: ['persist', 'remove'])]
    private ?Session $session = null;

    #[ORM\ManyToMany(targetEntity: Semester::class, inversedBy: 'courses')]
    private Collection $semesters;

    public function __construct()
    {
        $this->branches = new ArrayCollection();
        $this->semesters = new ArrayCollection();
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

    public function getTeacher(): ?Teacher
    {
        return $this->teacher;
    }

    public function setTeacher(?Teacher $teacher): static
    {
        $this->teacher = $teacher;

        return $this;
    }

    /**
     * @return Collection<int, Branch>
     */
    public function getBranches(): Collection
    {
        return $this->branches;
    }

    public function addBranch(Branch $branch): static
    {
        if (!$this->branches->contains($branch)) {
            $this->branches->add($branch);
            $branch->addCourse($this);
        }

        return $this;
    }

    public function removeBranch(Branch $branch): static
    {
        if ($this->branches->removeElement($branch)) {
            $branch->removeCourse($this);
        }

        return $this;
    }

    public function getCourseDuration(): int
    {
        return $this->course_duration;
    }

    public function setCourseDuration(int $course_duration): static
    {
        $this->course_duration = $course_duration;

        return $this;
    }

    public function getSession(): ?Session
    {
        return $this->session;
    }

    public function setSession(Session $session): static
    {
        // set the owning side of the relation if necessary
        if ($session->getCourse() !== $this) {
            $session->setCourse($this);
        }

        $this->session = $session;

        return $this;
    }

    /**
     * @return Collection<int, Semester>
     */
    public function getSemesters(): Collection
    {
        return $this->semesters;
    }

    public function addSemester(Semester $semester): static
    {
        if (!$this->semesters->contains($semester)) {
            $this->semesters->add($semester);
        }

        return $this;
    }

    public function removeSemester(Semester $semester): static
    {
        $this->semesters->removeElement($semester);

        return $this;
    }


}
