<?php

namespace App\Services\Decorators;


use App\Entity\Classroom;
use App\Entity\Course;
use App\Entity\Session;
use App\Entity\Teacher;
use App\Entity\TimeSchedule;
use Doctrine\ORM\EntityManagerInterface;
abstract class SessionDecorator
{
    public function __construct(protected EntityManagerInterface $entityManager)
    {
    }

    public function getAllSessions(): array
    {
        return $this->entityManager->getRepository(Session::class)->findAll();
    }


    public function getTimeScheduleSessions(TimeSchedule $timeSchedule): array
    {
        return $this->entityManager->getRepository(Session::class)->findBy(['timeSchedule' => $timeSchedule]);
    }
    public function getSessionCourse(Session $session): Course
    {
        return $session->getCourse();
    }
    public function getSessionTeacher(Session $session): Teacher
    {
        return $this->getSessionCourse($session)->getTeacher();
    }
    public function getSessionClassroom(Session $session): Classroom
    {
        return $session->getClassroom();
    }
    public function getSessionDay(Session $session): int
    {
        return $session->getDay();
    }
    public function getSessionTimeSlot(Session $session): array
    {
        return [$session->getStartHour(), $session->getEndHour()];
    }
}