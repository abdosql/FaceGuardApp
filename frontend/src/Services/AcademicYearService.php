<?php

namespace App\Services;

use App\Entity\AcademicYear;
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
}