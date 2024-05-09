<?php

namespace App\Services;

use App\Entity\TimeSchedule;
use App\Services\AutoGeneratorsServices\TimeScheduleGenerator;
use App\Services\settingsServices\ScheduleSettingsService;
use Doctrine\ORM\EntityManagerInterface;

class ScheduleService extends TimeScheduleGenerator
{


    public function __construct(ScheduleSettingsService $scheduleSettingsService, AcademicYearService $academicYearService, CourseService $courseService,SemesterService $semesterService,
                                EntityManagerInterface $entityManager,
    )
    {
        parent::__construct($scheduleSettingsService, $academicYearService, $courseService, $semesterService, $entityManager);
    }
}