<?php

namespace App\Services;

use App\Entity\Attendance;
use App\Entity\Session;
use App\Entity\Student;
use App\Services\userServices\StudentService;
use Doctrine\ORM\EntityManagerInterface;

class AttendanceService
{
    public function __construct(private EntityManagerInterface $entityManager, private SessionService $sessionService, private StudentService $studentService,private RfidService $rfidService)
    {
    }

    public function getStudentCurrentSessionAttendance(Student $student, Session $session): bool
    {
        $attendance = $this->entityManager->getRepository(Attendance::class)->findOneBy(['session' => $session, 'student' => $student]);
        if (!$attendance){
            return false;
        }
        return $attendance->isAttending();
    }

    public function studentIsPresent(string $rfid): array
    {
        $rfid = $this->rfidService->getRfidCard($rfid);
        if (!$rfid){
            return ['status' => "null"];
        }
        $student = $this->studentService->getStudentByRfid($rfid);
        if (!$student){
            return ['status' => "null"];
        }
        $session = $this->sessionService->getCurrentSessionForStudent($student);
        if (!$session)
        {
            return ['status' => "Declined"];
        }
        $attendance = new Attendance();
        $attendance->setStudent($student);
        $attendance->setSession($session);
        $attendance->setAttending(true);
        $this->entityManager->persist($attendance);
        $this->entityManager->flush();
        return ['status' => "success"];

    }
}