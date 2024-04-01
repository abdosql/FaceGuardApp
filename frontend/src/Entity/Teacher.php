<?php

namespace App\Entity;

use App\Repository\TeacherRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TeacherRepository::class)]
class Teacher extends User
{
    #[ORM\OneToMany(targetEntity: Course::class, mappedBy: 'teacher')]
    private Collection $courses;
    #[ORM\ManyToMany(targetEntity: Student::class, inversedBy: 'teachers')]
    private Collection $students;

    #[ORM\ManyToMany(targetEntity: Branch::class, mappedBy: 'teachers')]
    private Collection $branches;

    public function __construct()
    {
        $this->courses = new ArrayCollection();
        $this->students = new ArrayCollection();
        $this->branches = new ArrayCollection();
    }

    /**
     * @return Collection<int, Course>
     */
    public function getCourses(): Collection
    {
        return $this->courses;
    }
    public function getDisplayCourses(): string
    {
        $displayCourses = "";
        foreach ($this->courses as $course){
            $displayCourses .= $course->getCourseName(). ", ";
        }
        return $displayCourses;
    }

    public function addCourse(Course $course): static
    {
        if (!$this->courses->contains($course)) {
            $this->courses->add($course);
            $course->setTeacher($this);
        }

        return $this;
    }

    public function removeCourse(Course $course): static
    {
        if ($this->courses->removeElement($course)) {
            // set the owning side to null (unless already changed)
            if ($course->getTeacher() === $this) {
                $course->setTeacher(null);
            }
        }

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
        }

        return $this;
    }

    public function removeStudent(Student $student): static
    {
        $this->students->removeElement($student);

        return $this;
    }

    /**
     * @return Collection<int, Branch>
     */
    public function getBranches(): Collection
    {
        return $this->branches;
    }

    public function getDisplayBranches(): string
    {
        $displayBranches = "";
        foreach ($this->branches as $branch){
            $displayBranches .= $branch->getBranchName(). ", ";
        }
        return $displayBranches;
    }

    public function addBranch(Branch $branch): static
    {
        if (!$this->branches->contains($branch)) {
            $this->branches->add($branch);
            $branch->addTeacher($this);
        }

        return $this;
    }

    public function removeBranch(Branch $branch): static
    {
        if ($this->branches->removeElement($branch)) {
            $branch->removeTeacher($this);
        }

        return $this;
    }
}
