<?php

namespace App\Services;

use App\Entity\AcademicYear;
use App\Entity\Group;
use Doctrine\ORM\EntityManagerInterface;

class AcademicYearService
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }
    public function getAllAcademicYears(): array
    {
        return $this->entityManager->getRepository(AcademicYear::class)->findAll();
    }

    public function getAcademicYearWithBranches():array
    {
        return $this->entityManager->getRepository(AcademicYear::class)->getAcademicYearWithBranches();
    }
    public function getAcademicYearByYear(string $year): AcademicYear
    {
        return $this->entityManager->getRepository(AcademicYear::class)->findOneBy(["year" => $year]);
    }

}