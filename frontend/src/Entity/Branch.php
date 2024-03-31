<?php

namespace App\Entity;

use App\Repository\BrancheRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BrancheRepository::class)]
class Branch
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $branch_name = null;

    #[ORM\ManyToMany(targetEntity: Teacher::class, inversedBy: 'branches')]
    private Collection $teachers;

    #[ORM\OneToMany(targetEntity: Student::class, mappedBy: 'branche')]
    private Collection $students;

    #[ORM\ManyToMany(targetEntity: Course::class, inversedBy: 'branches')]
    private Collection $courses;

    public function __construct()
    {
        $this->teachers = new ArrayCollection();
        $this->students = new ArrayCollection();
        $this->courses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBranchName(): ?string
    {
        return $this->branch_name;
    }

    public function setBranchName(string $branch_name): static
    {
        $this->branch_name = $branch_name;

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
        }

        return $this;
    }

    public function removeTeacher(Teacher $teacher): static
    {
        $this->teachers->removeElement($teacher);

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
            $student->setBranche($this);
        }

        return $this;
    }

    public function removeStudent(Student $student): static
    {
        if ($this->students->removeElement($student)) {
            // set the owning side to null (unless already changed)
            if ($student->getBranche() === $this) {
                $student->setBranche(null);
            }
        }

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
        }

        return $this;
    }

    public function removeCourse(Course $course): static
    {
        $this->courses->removeElement($course);

        return $this;
    }
}
