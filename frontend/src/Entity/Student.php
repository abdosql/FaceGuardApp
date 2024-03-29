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
    #[ORM\JoinColumn(nullable: false)]
    private ?Group $group_ = null;

    #[ORM\ManyToOne(inversedBy: 'students')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Level $Level = null;

    #[ORM\ManyToMany(targetEntity: Teacher::class, mappedBy: 'students')]
    private Collection $teachers;

    #[ORM\OneToOne(inversedBy: 'student', cascade: ['persist', 'remove'])]
    private ?RFIDCard $rfidCard = null;

    #[ORM\OneToOne(inversedBy: 'student', cascade: ['persist', 'remove'])]
    private ?FacialRecognitionLog $facialRecognition = null;

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

    public function getLevel(): ?Level
    {
        return $this->Level;
    }

    public function setLevel(?Level $Level): static
    {
        $this->Level = $Level;

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
}
