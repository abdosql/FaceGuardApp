<?php

namespace App\Services;

use App\Entity\Semester;
use App\Services\settingsServices\ScheduleSettingsService;
use Doctrine\ORM\EntityManagerInterface;

class SemesterService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ScheduleSettingsService $scheduleSettingsService,
    )
    {
    }

    public function getAllSemesters(): array
    {
        return $this->entityManager->getRepository(Semester::class)->findAll();
    }

    /**
     * @throws \Exception
     */
    public function semesterNumberOfWeeks(array $semester): int
    {
        if ($semester["start"] && $semester["end"]) {
            $startDate = $semester["start"];
            $endDate = $semester["end"];

            $numberOfDays = $endDate->diff($startDate)->days;
            return $numberOfDays/7;
        } else {
            throw new \Exception("Start or end date of the semester is null");
        }
    }
}