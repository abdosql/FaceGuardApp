<?php

namespace App\Entity;

use App\Repository\TeacherRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TeacherRepository::class)]
class Teacher
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToMany(targetEntity: Course::class, mappedBy: 'teacher')]
    private Collection $Courses;

    #[ORM\OneToOne(mappedBy: 'teacher', cascade: ['persist', 'remove'])]
    private ?User $user = null;

    public function __construct()
    {
        $this->Courses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Course>
     */
    public function getCourses(): Collection
    {
        return $this->Courses;
    }

    public function addCourse(Course $course): static
    {
        if (!$this->Courses->contains($course)) {
            $this->Courses->add($course);
            $course->setTeacher($this);
        }

        return $this;
    }

    public function removeCourse(Course $course): static
    {
        if ($this->Courses->removeElement($course)) {
            // set the owning side to null (unless already changed)
            if ($course->getTeacher() === $this) {
                $course->setTeacher(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        // unset the owning side of the relation if necessary
        if ($user === null && $this->user !== null) {
            $this->user->setTeacher(null);
        }

        // set the owning side of the relation if necessary
        if ($user !== null && $user->getTeacher() !== $this) {
            $user->setTeacher($this);
        }

        $this->user = $user;

        return $this;
    }
}
