<?php
// src/Service/TimeScheduleGenerator.php

namespace App\Services\AutoGeneratorsServices;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\{TimeSchedule, Session};
use App\Services\{AcademicYearService,
    ClassroomService,
    CourseService,
    GroupService,
    ScheduleApiService,
    SemesterService,
    settingsServices\ScheduleSettingsService,
    userServices\TeacherService};
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;

use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class TimeScheduleGenerator
{
    public function __construct(
        private ScheduleSettingsService  $scheduleSettingsService,
        private AcademicYearService      $academicYearService,
        private CourseService            $courseService,
        protected EntityManagerInterface $entityManager,
        private ClassroomService         $classroomService,
        private TeacherService           $teacherService,
        private GroupService             $groupService,
        private ScheduleApiService       $scheduleApiService,
        private SemesterService          $semesterService,
        private LoggerInterface          $logger
    ) {
    }

    /**
     * @throws \Exception
     */
    public function generateSchedules(): array
    {
        $groups = $this->fetchGroups(1);
        $semesterWeeks = $this->semesterService->semesterNumberOfWeeks($this->scheduleSettingsService->getFallSemesterPeriod());
        $course_sessions = $this->numberOfSessionsPerWeekForEachCourse($semesterWeeks);
        $classrooms = $this->classroomService->getNumberOfClassrooms();
        $teachers = $this->fetchTeachers(1);
        $requestData = [
            "groups" => $groups,
            "teachers" => $teachers,
            "classrooms" => $classrooms,
            "course_sessions" => $course_sessions,
            "days" => 6,
            "slots_per_day" => 4
        ];



        try {
            return $this->scheduleApiService->getSchedule($requestData);
        } catch (DecodingExceptionInterface|TransportExceptionInterface $e) {
            // Log the exception message
            $this->logger->error('Error generating schedules: ' . $e->getMessage());

            // Optionally, you can include a status code in the array
            return [
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Unable to generate schedules. Please check the data or API.'
            ];
        }
    }

    private function fetchGroups(int $id): array
    {
        $groupsOutput = [];
        $academicYears = $this->academicYearService->getAllAcademicYears();
        foreach ($academicYears as $academicYear) {
            foreach ($academicYear->getBranches() as $branch) {
                foreach ($academicYear->getGroups() as $group) {
                    foreach ($branch->getCourses() as $course) {
                        foreach ($course->getSemesters() as $semester) {
                            if ($semester->getId() == $id) {
                                if (!$group->branchExists($branch)){
                                    continue;
                                }
                                $groupsOutput[$academicYear->getId()][$branch->getId()][$group->getId()][] = $course->getId();

                            }
                        }
                    }
                }
            }
        }

        return $groupsOutput;
    }

    private function fetchTeachers(int $semesterId): array
    {
        $teachersOutput = [];
        $teachers = $this->teacherService->getAllTeachers();
        foreach ($teachers as $teacher) {
            foreach ($teacher->getCourses() as $course) {
                foreach ($course->getSemesters() as $semester) {
                    if ($semester->getId() == $semesterId){
                        $teachersOutput[$teacher->getId()][] = $course->getId();
                    }
                }
            }
        }
        return $teachersOutput;
    }

    /**
     * @throws \Exception
     */
    public function saveSchedules(array $schedules, int $semesterId): void
    {
        $semester = $this->semesterService->getSemesterById($semesterId);
        $sessionsTimeSlots = $this->generateSessionsTimeSlots();

        foreach ($schedules as $years => $branches) {
            foreach ($branches as $branch => $groups) {
                foreach ($groups as $groupId => $days) {
                    $groupEntity = $this->groupService->getGroupById($groupId);

                    // Check if a TimeSchedule already exists for this group and semester
                    $existingSchedule = $this->entityManager->getRepository(TimeSchedule::class)->findOneBy([
                        'group_' => $groupEntity,
                        'semester' => $semester
                    ]);

                    if ($existingSchedule) {
                        error_log("Schedule for Group ID $groupId and Semester ID $semesterId already exists.");
                        continue;
                    }

                    $schedule = new TimeSchedule();
                    $schedule->setGroup($groupEntity);
                    $schedule->setSemester($semester);
                    $this->entityManager->persist($schedule);

                    foreach ($days as $day => $sessions) {
                        foreach ($sessions as $session => $data) {
                            if ($data != null) {
                                $course = $this->courseService->getCourseById($data[0]);
                                $classroom = $this->classroomService->getClassroomById($data[2] + 1);
                                if (!$course || !$classroom) {
                                    error_log("Course ID {$data[0]} or Classroom ID {$data[2]} not found.");
                                    continue;
                                }

                                error_log("Creating session for Group ID $groupId, Day $day, Session $session");

                                $newSession = new Session();
                                $newSession->setDay($day);
                                $newSession->setStartHour($sessionsTimeSlots[$session]["start_time"]);
                                $newSession->setEndHour($sessionsTimeSlots[$session]["end_time"]);
                                $newSession->setCourse($course);
                                $newSession->setClassroom($classroom);
                                $newSession->setTimeSchedule($schedule);
                                $this->entityManager->persist($newSession);
                            }
                        }
                    }

                    // Persist and flush the schedule and its sessions
                    $this->entityManager->flush();
                }
            }
        }

        // Final flush to ensure all data is persisted
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



        foreach ($this->createSessionsSlots($morning_slot, $pause_duration, $sessions_duration) as $session) {
            $timeSlots[] = $session;
        }

        foreach ($this->createSessionsSlots($evening_slot, $pause_duration, $sessions_duration) as $session) {
            $timeSlots[] = $session;
        }
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
                "start_time" => $startTime,
                "end_time" => $endTime,
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
            $courses[$course->getId()] = $numberOfSessionsPerWeek;

        }
        return $courses;
    }
}