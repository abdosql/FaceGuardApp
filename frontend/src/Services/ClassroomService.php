<?php

namespace App\Services;

use App\Entity\Classroom;
use Doctrine\ORM\EntityManagerInterface;

class ClassroomService
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function getAllClassrooms(): array
    {
        return $this->entityManager->getRepository(Classroom::class)->findAll();
    }

    public function getClassroomById(Classroom $classroom): Classroom
    {
        return $this->entityManager->getRepository(Classroom::class)->find($classroom);
    }

    public function createClassroom(Classroom $classroom): void
    {
        $this->entityManager->persist($classroom);
        $this->entityManager->flush();
    }

    public function classroomIsAvailable(Classroom $classroom): bool
    {
        return $classroom->isAvailable();
    }

}