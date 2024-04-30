<?php

namespace App\Entity;

use App\Repository\StudentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StudentRepository::class)]
class Student extends User
{
    #[ORM\ManyToOne(inversedBy: 'students')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Group $group_ = null;

    #[ORM\ManyToMany(targetEntity: Teacher::class, mappedBy: 'students')]
    private Collection $teachers;

    #[ORM\OneToOne(inversedBy: 'student', cascade: ['persist', 'remove'])]
    private ?RFIDCard $rfidCard = null;

    #[ORM\OneToOne(inversedBy: 'student', cascade: ['persist', 'remove'])]
    private ?FacialRecognitionLog $facialRecognition = null;

    #[ORM\ManyToOne(inversedBy: 'students')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Branch $branch = null;

    #[ORM\ManyToOne(inversedBy: 'students')]
    private ?AcademicYear $academicYear = null;

    #[ORM\OneToOne(mappedBy: 'student', cascade: ['persist', 'remove'])]
    private ?Attendance $attendance = null;

    public function __construct()
    {
        $this->teachers = new ArrayCollection();
    }

    public function getGroup(): ?Group
    {
        return $this->group_;
    }

    public function setGroup(?Group $group_): static
    {
        $this->group_ = $group_;

        return $this;
    }

    /**
     * @return Collection<int, Teacher>
     */
    public function getTeachers(): Collection
    {
        return $this->teachers;
    }

    public function addTeacher(Teacher $teacher): static
    {
        if (!$this->teachers->contains($teacher)) {
            $this->teachers->add($teacher);
            $teacher->addStudent($this);
        }

        return $this;
    }

    public function removeTeacher(Teacher $teacher): static
    {
        if ($this->teachers->removeElement($teacher)) {
            $teacher->removeStudent($this);
        }

        return $this;
    }

    public function getRfidCard(): ?RFIDCard
    {
        return $this->rfidCard;
    }

    public function setRfidCard(?RFIDCard $rfidCard): static
    {
        $this->rfidCard = $rfidCard;

        return $this;
    }

    public function getFacialRecognition(): ?FacialRecognitionLog
    {
        return $this->facialRecognition;
    }

    public function setFacialRecognition(?FacialRecognitionLog $facialRecognition): static
    {
        $this->facialRecognition = $facialRecognition;

        return $this;
    }

    public function getBranch(): ?Branch
    {
        return $this->branch;
    }

    public function setBranch(?Branch $branch): static
    {
        $this->branch = $branch;

        return $this;
    }

    public function getAcademicYear(): ?AcademicYear
    {
        return $this->academicYear;
    }

    public function setAcademicYear(?AcademicYear $academicYear): static
    {
        $this->academicYear = $academicYear;

        return $this;
    }

    public function getAttendance(): ?Attendance
    {
        return $this->attendance;
    }

    public function setAttendance(Attendance $attendance): static
    {
        // set the owning side of the relation if necessary
        if ($attendance->getStudent() !== $this) {
            $attendance->setStudent($this);
        }

        $this->attendance = $attendance;

        return $this;
    }
}
