<?php

namespace App\Services;

use App\Entity\Course;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;

class CourseService
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function getAllCourses(): array
    {
        return $this->entityManager->getRepository(Course::class)->findAll();
    }

    public function getCourseById(int $id): Course
    {
        return $this->entityManager->getRepository(Course::class)->find($id);
    }
    public function getCourseDuration(Course $course): int
    {
        return $course->getCourseDuration();
    }

    public function getSemesters(Course $course): Collection
    {
        return $course->getSemesters();
    }
    public function numberOfSessionsNeeded(Course $course, \DateTime $sessionDuration): int
    {
        $courseDurationPerMinute = $this->getCourseDuration($course) * 60;
        $sessionMinutes = $sessionDuration->format('H') * 60 + (int)$sessionDuration->format('i');
        return ceil($courseDurationPerMinute/$sessionMinutes);
    }

    public function numberOfSessionsPerWeek(int $courseNumberOfSessions, int $numberOfWeeks): int
    {
        return ceil($courseNumberOfSessions / $numberOfWeeks);
    }
    public function allYearLong(Course $course): ?bool
    {
        return $course->getSemesters()->count() > 1;
    }
}