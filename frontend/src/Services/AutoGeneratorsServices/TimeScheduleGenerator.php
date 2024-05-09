<?php

namespace App\Services\AutoGeneratorsServices;

use App\Entity\Classroom;
use App\Entity\Course;
use App\Entity\Group;
use App\Entity\Semester;
use App\Entity\Session;
use App\Entity\TimeSchedule;
use App\Services\AcademicYearService;
use App\Services\CourseService;
use App\Services\SemesterService;
use App\Services\settingsServices\ScheduleSettingsService;
use Doctrine\ORM\EntityManagerInterface;

class TimeScheduleGenerator
{
    public function __construct(
        private ScheduleSettingsService $scheduleSettingsService,
        private AcademicYearService $academicYearService,
        private CourseService $courseService,
        private SemesterService $semesterService,
        private EntityManagerInterface $entityManager,
    )
    {
    }

    public function getTimeScheduleByGroup(Group $group): TimeSchedule
    {
        return $group->getTimeSchedule();
    }

    /**
     * @throws \Exception
     */
    public function generateSchedules(): void
    {
        foreach ($this->academicYearService->getAllAcademicYears() as $academicYear)
        {
            foreach ($academicYear->getBranches() as $branch){
                foreach ($branch->getCourses() as $course)
                {
                    foreach ($branch->getGroups() as $group)
                    {
                        $timeSchedule = new TimeSchedule();
                        $group->setTimeSchedule($timeSchedule);
                        $this->entityManager->persist($timeSchedule);
                        dd($this->generateSessionsForCourse($group, $course, "Fall"));
                    }
                }
            }
        }
    }

    /**
     * @throws \Exception
     */
    public function generateSessionsForCourse(Group $group, Course $course, string $semester): int
    {
        $sessionsSlots = $this->generateSessionsTimeSlots();
        $semesterWeeks = 0;
        if ($semester == "Fall"){
            $semesterWeeks = $this->semesterService->semesterNumberOfWeeks($this->scheduleSettingsService->getFallSemesterPeriod());
        }else{
            $semesterWeeks = $this->semesterService->semesterNumberOfWeeks($this->scheduleSettingsService->getSpringSemesterPeriod());
        }
        $courseNumberOfSessions = $this->courseService->numberOfSessionsNeeded($course, $this->scheduleSettingsService->getSessionDuration());
        if ($this->courseService->getSemesters($course)->count() == 2){
            $courseNumberOfSessions /= 2;
        }
        $numberOfSessionsPerWeek = $this->courseService->numberOfSessionsPerWeek($courseNumberOfSessions, $semesterWeeks);

        $sessionsCreated = 0;
        for ($i = 0; $i < $numberOfSessionsPerWeek; $i++) {
            foreach ($sessionsSlots as $period => $slots) {
                foreach ($slots as $sessionSlot) {
                    if ($this->findAvailableTimeSlot($sessionSlot, $group)) {
                        $session = new Session();
                        $session->setCourse($course);
                        $session->setStartHour(new \DateTime($sessionSlot["start_time"]));
                        $session->setEndHour(new \DateTime($sessionSlot["end_time"]));
                        $group->getTimeSchedule()->addSession($session);
                        $sessionsCreated++;
                        $this->entityManager->persist($session);
                    }
                }
            }
        }
        $this->entityManager->flush();

        return $sessionsCreated;

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

    public function selectRandomClassroom(): Classroom
    {

    }

    /**
     * @throws \Exception
     */
    private function findAvailableTimeSlot(array $timeSlot, Group $group): bool
    {
        $start = new \DateTime($timeSlot["start_time"]);
        $end = new \DateTime($timeSlot["end_time"]);
        foreach ($this->getTimeScheduleByGroup($group)->getSessions() as $session)
        {
            if ($session->getStartHour() == $start and $session->getEndHour() == $end)
            {
                return true;
            }
        }
        return false;
    }
    public function isTeacherAvailable(): bool
    {
        
    }




}