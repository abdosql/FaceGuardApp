<?php

namespace App\Entity;

use App\Repository\SemestreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    #[ORM\ManyToMany(targetEntity: Course::class, mappedBy: 'semesters')]
    private Collection $courses;

    /**
     * @var Collection<int, TimeSchedule>
     */
    #[ORM\OneToMany(targetEntity: TimeSchedule::class, mappedBy: 'semester', orphanRemoval: true)]
    private Collection $timeSchedules;

    public function __construct()
    {
        $this->courses = new ArrayCollection();
        $this->timeSchedules = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, Course>
     */
    public function getCourses(): Collection
    {
        return $this->courses;
    }

    public function addCourse(Course $course): static
    {
        if (!$this->courses->contains($course)) {
            $this->courses->add($course);
            $course->addSemester($this);
        }

        return $this;
    }

    public function removeCourse(Course $course): static
    {
        if ($this->courses->removeElement($course)) {
            $course->removeSemester($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, TimeSchedule>
     */
    public function getTimeSchedules(): Collection
    {
        return $this->timeSchedules;
    }

    public function addTimeSchedule(TimeSchedule $timeSchedule): static
    {
        if (!$this->timeSchedules->contains($timeSchedule)) {
            $this->timeSchedules->add($timeSchedule);
            $timeSchedule->setSemester($this);
        }

        return $this;
    }

    public function removeTimeSchedule(TimeSchedule $timeSchedule): static
    {
        if ($this->timeSchedules->removeElement($timeSchedule)) {
            // set the owning side to null (unless already changed)
            if ($timeSchedule->getSemester() === $this) {
                $timeSchedule->setSemester(null);
            }
        }

        return $this;
    }
}
