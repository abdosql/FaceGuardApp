<?php

namespace App\Entity;

use AllowDynamicProperties;
use App\Repository\SessionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[AllowDynamicProperties] #[ORM\Entity(repositoryClass: SessionRepository::class)]
class Session
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $start_hour = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $end_hour = null;

    #[ORM\OneToMany(targetEntity: Attendance::class, mappedBy: 'session')]
    private Collection $attendances;

    #[ORM\Column]
    private ?int $day = null;


    #[ORM\ManyToOne(inversedBy: 'sessions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?TimeSchedule $timeSchedule = null;

    #[ORM\ManyToOne(inversedBy: 'sessions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Classroom $classroom = null;

    #[ORM\ManyToOne(inversedBy: 'sessions')]
    private ?Course $course = null;

    public function __construct()
    {
        $this->timeSchedules = new ArrayCollection();
        $this->attendances = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartHour(): ?\DateTimeInterface
    {
        return $this->start_hour;
    }

    public function setStartHour(\DateTimeInterface $start_hour): static
    {
        $this->start_hour = $start_hour;

        return $this;
    }

    public function getEndHour(): ?\DateTimeInterface
    {
        return $this->end_hour;
    }

    public function setEndHour(\DateTimeInterface $end_hour): static
    {
        $this->end_hour = $end_hour;

        return $this;
    }

    /**
     * @return Collection<int, Attendance>
     */
    public function getAttendances(): Collection
    {
        return $this->attendances;
    }

    public function addAttendance(Attendance $attendance): static
    {
        if (!$this->attendances->contains($attendance)) {
            $this->attendances->add($attendance);
            $attendance->setSession($this);
        }

        return $this;
    }

    public function removeAttendance(Attendance $attendance): static
    {
        if ($this->attendances->removeElement($attendance)) {
            // set the owning side to null (unless already changed)
            if ($attendance->getSession() === $this) {
                $attendance->setSession(null);
            }
        }

        return $this;
    }

    public function getDay(): ?int
    {
        return $this->day;
    }

    public function setDay(int $day): static
    {
        $this->day = $day;

        return $this;
    }

    public function getTimeSchedule(): ?TimeSchedule
    {
        return $this->timeSchedule;
    }

    public function setTimeSchedule(?TimeSchedule $timeSchedule): static
    {
        $this->timeSchedule = $timeSchedule;

        return $this;
    }

    public function getClassroom(): ?Classroom
    {
        return $this->classroom;
    }

    public function setClassroom(?Classroom $classroom): static
    {
        $this->classroom = $classroom;

        return $this;
    }

    public function getCourse(): ?Course
    {
        return $this->course;
    }

    public function setCourse(?Course $course): static
    {
        $this->course = $course;

        return $this;
    }
}
