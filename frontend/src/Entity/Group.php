<?php

namespace App\Entity;

use App\Repository\GroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GroupRepository::class)]
#[ORM\Table(name: '`group`')]
class Group
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $group_name = null;

    #[ORM\OneToMany(targetEntity: Student::class, mappedBy: 'group_')]
    private Collection $students;

    #[ORM\OneToOne(mappedBy: 'group_', cascade: ['persist', 'remove'])]
    private ?TimeSchedule $timeSchedule = null;

    #[ORM\ManyToMany(targetEntity: AcademicYear::class, inversedBy: 'groups')]
    private Collection $academicYear;

    #[ORM\ManyToMany(targetEntity: Branch::class, inversedBy: 'groups')]
    private Collection $branches;

    public function __construct()
    {
        $this->students = new ArrayCollection();
        $this->academicYear = new ArrayCollection();
        $this->branches = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGroupName(): ?string
    {
        return $this->group_name;
    }

    public function setGroupName(string $group_name): static
    {
        $this->group_name = $group_name;

        return $this;
    }

    /**
     * @return Collection<int, Student>
     */
    public function getStudents(): Collection
    {
        return $this->students;
    }

    public function addStudent(Student $student): static
    {
        if (!$this->students->contains($student)) {
            $this->students->add($student);
            $student->setGroup($this);
        }

        return $this;
    }
    public function addStudents(Collection $students): void
    {
        foreach ($students as $student) {
            $this->addStudent($student);
        }
    }
    public function removeStudent(Student $student): static
    {
        if ($this->students->removeElement($student)) {
            // set the owning side to null (unless already changed)
            if ($student->getGroup() === $this) {
                $student->setGroup(null);
            }
        }

        return $this;
    }

    public function getTimeSchedule(): ?TimeSchedule
    {
        return $this->timeSchedule;
    }

    public function setTimeSchedule(TimeSchedule $timeSchedule): static
    {
        // set the owning side of the relation if necessary
        if ($timeSchedule->getGroup() !== $this) {
            $timeSchedule->setGroup($this);
        }

        $this->timeSchedule = $timeSchedule;

        return $this;
    }

    /**
     * @return Collection<int, AcademicYear>
     */
    public function getAcademicYear(): Collection
    {
        return $this->academicYear;
    }

    public function addAcademicYear(AcademicYear $academicYear): static
    {
        if (!$this->academicYear->contains($academicYear)) {
            $this->academicYear->add($academicYear);
        }

        return $this;
    }

    public function removeAcademicYear(AcademicYear $academicYear): static
    {
        $this->academicYear->removeElement($academicYear);

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
        }

        return $this;
    }

    public function removeBranch(Branch $branch): static
    {
        $this->branches->removeElement($branch);

        return $this;
    }

    public function branchExists(Branch $branch): bool
    {
        if ($this->branches->contains($branch)) {
            return true;
        }
        return false;
    }

    public function getDisplayName(): string
    {
        $academicYears = $this->academicYear->toArray();
        $branches = $this->branches->toArray();
        return "{$this->group_name} ({$academicYears[0]->getYear()} - {$branches[0]->getBranchName()})";
    }
}
