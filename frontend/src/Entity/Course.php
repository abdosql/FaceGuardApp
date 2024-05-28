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

    #[ORM\ManyToMany(targetEntity: Semester::class, inversedBy: 'courses')]
    private Collection $semesters;

    /**
     * @var Collection<int, Session>
     */
    #[ORM\OneToMany(targetEntity: Session::class, mappedBy: 'course')]
    private Collection $sessions;

    /**
     * @var Collection<int, AcademicYear>
     */
    #[ORM\ManyToMany(targetEntity: AcademicYear::class, inversedBy: 'courses')]
    private Collection $academicYears;

    public function __construct()
    {
        $this->branches = new ArrayCollection();
        $this->semesters = new ArrayCollection();
        $this->sessions = new ArrayCollection();
        $this->academicYears = new ArrayCollection();
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
            $session->setCourse($this);
        }

        return $this;
    }

    public function removeSession(Session $session): static
    {
        if ($this->sessions->removeElement($session)) {
            // set the owning side to null (unless already changed)
            if ($session->getCourse() === $this) {
                $session->setCourse(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, AcademicYear>
     */
    public function getAcademicYears(): Collection
    {
        return $this->academicYears;
    }

    public function addAcademicYear(AcademicYear $academicYear): static
    {
        if (!$this->academicYears->contains($academicYear)) {
            $this->academicYears->add($academicYear);
        }

        return $this;
    }

    public function removeAcademicYear(AcademicYear $academicYear): static
    {
        $this->academicYears->removeElement($academicYear);

        return $this;
    }


}
