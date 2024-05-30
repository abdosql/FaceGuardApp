<?php

namespace App\Services;


use App\Entity\Session;

use App\Entity\Student;
use App\Services\Decorators\SessionDecorator;
use Doctrine\ORM\EntityManagerInterface;

class SessionService extends SessionDecorator
{

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager);
    }

    public function findTeacherSessions($teacherId)
    {
        return $this->entityManager->getRepository(Session::class)->findTeacherSessions($teacherId);
    }
    public function findStudentSessions($studentId)
    {
        return $this->entityManager->getRepository(Session::class)->findStudentSessions($studentId);
    }

    public function getCurrentSessionForStudent(Student $student): ?Session
    {
        $sessions = $this->findStudentSessions($student->getId());
        return $this->extracted($sessions);
    }
    public function getCurrentSessionForTeacher(int $teacher): ?Session
    {
        $sessions = $this->findTeacherSessions($teacher);
        return $this->extracted($sessions);
    }

    /**
     * @param $sessions
     * @return ?Session
     */
    public function extracted($sessions): ?Session
    {
        $currentTime = new \DateTime('now');
        $currentHour = (int)$currentTime->format('H:i');

        foreach ($sessions as $session) {
            $startHour = (int)$session->getStartHour()->format('H:i');
            $endHour = (int)$session->getEndHour()->format('H:i');

            if ($currentHour >= $startHour && $currentHour < $endHour) {
                return $session;
            }
        }
        return null;
    }
}