<?php
// src/Service/TimeScheduleGenerator.php

namespace App\Services\AutoGeneratorsServices;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\{Course, Group, TimeSchedule, Session};
use App\Services\{AcademicYearService,
    ClassroomService,
    CourseService,
    GroupService,
    ScheduleApiService,
    SemesterService,
    settingsServices\ScheduleSettingsService,
    userServices\TeacherService};
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class TimeScheduleGenerator
{
    public function __construct(
        private ScheduleSettingsService $scheduleSettingsService,
        private AcademicYearService $academicYearService,
        private CourseService $courseService,
        private EntityManagerInterface $entityManager,
        private ClassroomService $classroomService,
        private TeacherService $teacherService,
        private GroupService $groupService,
        private ScheduleApiService $scheduleApiService,
        private SemesterService $semesterService,
        private LoggerInterface $logger
    ) {
    }

    /**
     * @throws \Exception
     */
    public function generateSchedules(): array
    {
        $teachers = $this->fetchTeachers();
        $groups = $this->fetchGroups();
        $semesterWeeks = $this->semesterService->semesterNumberOfWeeks($this->scheduleSettingsService->getFallSemesterPeriod());
        $course_sessions = $this->numberOfSessionsPerWeekForEachCourse($semesterWeeks);
        $classrooms = $this->classroomService->getAllClassrooms();

        $requestData = [
            "groups" => $groups,
            "teachers" => $teachers,
            "classrooms" => 50,
            "course_sessions" => $course_sessions
        ];
        dd($requestData);
        try {
            return $this->scheduleApiService->getSchedule($requestData);
        } catch (\Exception $e) {
            // Log the exception message
            $this->logger->error('Error generating schedules: ' . $e->getMessage());

            // Optionally, you can include a status code in the array
            return [
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Unable to generate schedules. Please check the data or API.'
            ];
        }
    }

    private function fetchGroups(): array
    {

        $groupsOutput = [];
        $groups = $this->groupService->getAllGroups();
        foreach ($groups as $group) {
            foreach ($group->getBranches() as $branch){
                $groupsOutput[$group->getId()] = [];
                foreach ($branch->getCourses() as $course) {
                    $groupsOutput[$group->getId()][] = $course->getCourseName();
                }
            }
        }
        return $groupsOutput;
//        return [
//            1 => ["PHP", "ASP", "Spring", "Economics", "Business", "Big Data"],
//            // More groups
//        ];
    }

    private function fetchTeachers(): array
    {
        $teachersOutput = [];
        $teachers = $this->teacherService->getAllTeachers();
        foreach ($teachers as $teacher) {
            foreach ($teacher->getCourses() as $course) {
                $teachersOutput[$teacher->getDisplayName()] = $this->teacherService->getCoursesByTeacher($teacher);
            }
        }
        return $teachersOutput;
    }

    private function saveSchedules(array $schedules): void
    {
        foreach ($schedules as $groupId => $days) {
            $group = $this->entityManager->getRepository(Group::class)->find($groupId);
            if (!$group) continue;

            $timeSchedule = new TimeSchedule();
            $group->setTimeSchedule($timeSchedule);

            foreach ($days as $day => $slots) {
                foreach ($slots as $slot => $courseInfo) {
                    if ($courseInfo) {
                        [$courseName, $teacherName, $classroomNumber] = $courseInfo;
                        $course = $this->courseService->findByName($courseName);
                        $teacher = $this->teacherService->findByName($teacherName); // Ensure you have a way to get teachers
                        $classroom = $this->entityManager->getRepository(Classroom::class)->find($classroomNumber);

                        $session = new Session();
                        $session->setCourse($course);
                        $session->setTeacher($teacher);
                        $session->setClassroom($classroom);
                        $session->setStartHour(new \DateTime("08:30")); // Example, set correctly based on slot
                        $session->setEndHour(new \DateTime("10:30")); // Example, set correctly

                        $timeSchedule->addSession($session);
                    }
                }
            }
            $this->entityManager->persist($timeSchedule);
        }
        $this->entityManager->flush();
    }
    /**
     * @throws \Exception
     */
    public function generateSessionsTimeSlots(): array
    {

        $timeSlots = [];
        $sessions_duration = $this->scheduleSettingsService->getSessionDuration();
        $pause_duration = $this->scheduleSettingsService->pauseDuration();

        $morning_slot = $this->scheduleSettingsService->getMorningSlot();

        $evening_slot = $this->scheduleSettingsService->getEveningSlot();

        $timeSlots["morning"] = $this->createSessionsSlots($morning_slot, $pause_duration, $sessions_duration);

        $timeSlots["evening"] = $this->createSessionsSlots($evening_slot, $pause_duration, $sessions_duration);
        return $timeSlots;
    }

    /**
     * @throws \Exception
     */
    public function createSessionsSlots(array $slot, \DateTime|false $pause_duration, \DateTime|false $sessions_duration): array
    {
        $sessions = [];
        $slotNumberOfSessions = $this->calculateNumberOfSessionsNeededPerSlot($slot, $pause_duration, $sessions_duration);
        $startTime = $slot["start"];

        for ($i = 0; $i < $slotNumberOfSessions; $i++) {
            $endTime = clone $startTime;
            $endTime->add(new \DateInterval('PT' . $sessions_duration->format('h') . 'H' . $sessions_duration->format('i') . 'M'));

            $sessions["session " . $i] = [
                "start_time" => $startTime->format('H:i'),
                "end_time" => $endTime->format('H:i'),
            ];

            $startTime = clone $endTime;

            if ($pause_duration) {
                $pauseMinutes = $pause_duration->format('i');
                $startTime->add(new \DateInterval('PT' . $pauseMinutes . 'M'));
            }
        }

        return $sessions;
    }

    /**
     * @throws \Exception
     */
    public function calculateNumberOfSessionsNeededPerSlot($timeSlot, $pauseDuration, $sessionDuration): int
    {
        $endDiffStart = $timeSlot["end"]->diff($timeSlot["start"]);
        $pauseMinutes = $pauseDuration->format('H') * 60 + $pauseDuration->format('i');
        $sessionMinutes = $sessionDuration->format('H') * 60 + $sessionDuration->format('i');
        $minutes = (($endDiffStart->h * 60) + $endDiffStart->i) - $pauseMinutes;

        return $minutes/$sessionMinutes;
    }

    public function numberOfSessionsPerWeekForEachCourse(int $numberOfWeeks): array
    {
        $courses = [];
        $sessionDuration = $this->scheduleSettingsService->getSessionDuration();
        foreach ($this->courseService->getAllCourses() as $course) {
            $numberOfSessionNeededForCourse = $this->courseService->numberOfSessionsNeeded($course, $sessionDuration);
            $numberOfSessionsPerWeek = $this->courseService->numberOfSessionsPerWeek($numberOfSessionNeededForCourse, $numberOfWeeks);
            $courses[$course->getCourseName()] = $numberOfSessionsPerWeek;

        }
        return $courses;
    }
}