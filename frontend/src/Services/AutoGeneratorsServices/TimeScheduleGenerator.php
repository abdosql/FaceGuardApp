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
        dd($this->generateSessionsTimeSlots());
//        foreach ($this->academicYearService->getAllAcademicYears() as $academicYear)
//        {
//            foreach ($academicYear->getBranches() as $branch){
//                foreach ($branch->getCourses() as $course)
//                {
//                    foreach ($branch->getGroups() as $group)
//                    {
//                        $timeSchedule = new TimeSchedule();
//                        $group->setTimeSchedule($timeSchedule);
//                        $this->entityManager->persist($timeSchedule);
//                        dd($this->generateSessionsForCourse($group, $course, "Fall"));
//                    }
//                }
//            }
//        }
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


    public function prompt(): string
    {
        $exp = '
        {
   "dayName": {
      "sessionId": {
         "slot": "08:30 to 12:00",
         "group": "Group A",
         "branch": "Branch 1",
         "subject": "Mathematics",
         "teacher": "Mr. Smith"
      },
      "essionId": {
         "slot": "14:00 to 16:00",
         "group": "Group B",
         "branch": "Branch 2",
         "subject": "Physics",
         "teacher": "Mrs. Johnson"
      }
   },
        ';
        return "
                    You are an AI assistant tasked with generating a comprehensive time schedule for a school. The school has multiple branches, each with several groups of students, and these groups are divided by academic year. The time schedules need to be created for each group, in each branch, for each academic year.
                    you will have each group one by one.
                    Details:
                    
                    Constraints:
                    Academic year: First year
                    Courses: ['PHP => teacher: Abdelaziz Saqqal', 'ASP => teacher: khiro', 'Spring => teacher: Haddouti', 'Economics => teacher: latifa', 'Business => teacher: mani', 'Big Data => teacher: Mohammed']
                    Session Duration: Each session lasts for 2 hours.
                    Pause Duration: Each break between sessions is 30 minutes.
                    Daily Time Slots:
                    Morning: 08:30 to 13:00.
                    Evening: 14:00 to 18:30.
                    Weekend Days: Saturday and Sunday.
                    Fall Semester: 10 Oct, 2023 to 01 Feb, 2024.
                    Spring Semester: 10 Feb, 2024 to 01 Jul, 2024.
                    Additional Constraints:
                    Ensure teachers do not have back-to-back classes without a break.
                    Task: Generate a detailed, conflict-free time schedule based on the provided entities and constraints. The schedule should optimize for the best use of resources while ensuring that all educational and administrative requirements are met. Include error handling for scenarios where constraints cannot be fully satisfied, suggesting alternative solutions.
                    
                    Output: Provide the generated schedules in a structured JSON format, like the following example:
                    .'$exp'.
                    Return the complete time schedules for each day of the week, branch, and group in this format.
        ";
    }



}