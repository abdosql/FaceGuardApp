<?php

namespace App\Services;

use App\Entity\Session;
use App\Entity\TimeSchedule;
use App\Services\AutoGeneratorsServices\TimeScheduleGenerator;
use App\Services\settingsServices\ScheduleSettingsService;
use App\Services\userServices\TeacherService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class ScheduleService extends TimeScheduleGenerator{

    public function __construct(ScheduleSettingsService $scheduleSettingsService, AcademicYearService $academicYearService, CourseService $courseService, EntityManagerInterface $entityManager, ClassroomService $classroomService, TeacherService $teacherService, GroupService $groupService, ScheduleApiService $scheduleApiService, SemesterService $semesterService, LoggerInterface $logger)
    {
        parent::__construct($scheduleSettingsService, $academicYearService, $courseService, $entityManager, $classroomService, $teacherService, $groupService, $scheduleApiService, $semesterService, $logger);
    }

    public function getSchedules(): array
    {
        return $this->entityManager->getRepository(TimeSchedule::class)->findAll();
    }
    public function findBySemesterAndGroup($semesterId, $groupId)
    {
        return $this->entityManager->getRepository(TimeSchedule::class)->findBySemesterAndGroup($semesterId, $groupId);
    }
    public function findTeacherSessions($teacherId)
    {
        return $this->entityManager->getRepository(Session::class)->findTeacherSessions($teacherId);
    }
    public function fullCalenderDataStructure($semesterId, $groupId): array
    {
        $schedules = $this->findBySemesterAndGroup($semesterId, $groupId);

        $events = [];
        foreach ($schedules as $schedule) {
            foreach ($schedule->getSessions() as $session) {
                $events = $this->getEvents($session, $events,'groups');
            }
        }
        return $events;
    }
    public function fullCalenderDataStructureTeachers($teacherId): array
    {
        $sessions = $this->findTeacherSessions($teacherId);

        $events = [];
        foreach ($sessions as $session) {
            $events = $this->getEvents($session, $events, 'teachers');
        }
        return $events;
    }

    /**
     * @param mixed $session
     * @param array $events
     * @return array
     */
    public function getEvents(mixed $session, array $events, string $type): array
    {

        if ($type == 'teacher'){
            $events[] = [
                'id' => $session->getId(),
                'title' => $session->getCourse()->getCoursename(),
                'start' => $session->getDay() + 1 . 'T' . $session->getStartHour()->format('H:i:s'),
                'end' => $session->getDay() + 1 . 'T' . $session->getEndHour()->format('H:i:s'),
                'day' => $session->getDay(),
                'description' => '<strong>Classroom</strong>: ' . $session->getClassroom()->getClassroomNumber(),
            ];
        }else{
            $events[] = [
                'id' => $session->getId(),
                'title' => $session->getCourse()->getCoursename(),
                'start' => $session->getDay() + 1 . 'T' . $session->getStartHour()->format('H:i:s'),
                'end' => $session->getDay() + 1 . 'T' . $session->getEndHour()->format('H:i:s'),
                'day' => $session->getDay(),
                'description' => '<strong>Classroom</strong>: ' . $session->getClassroom()->getClassroomNumber(). '<br>'. '<strong>Teacher :</strong>'. $session->getCourse()->getTeacher()->getDisplayName(),
            ];
        }

        return $events;
    }

}