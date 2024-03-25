<?php

namespace App\Services\TeacherServices;

use App\Entity\Student;
use App\Entity\Teacher;
use Doctrine\ORM\EntityManagerInterface;

class TeacherService
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function getAllTeachers(): array
    {
        return $this->entityManager->getRepository(Teacher::class)->findAll();
    }

    public function getTeacherById(int $id): ?Teacher
    {
        return $this->entityManager->getRepository(Teacher::class)->find($id);
    }

    public function saveStudent(Student $student): void
    {
        $this->entityManager->persist($student);
        $this->entityManager->flush();
    }

    public function deleteStudent(Student $student): void
    {
        $this->entityManager->remove($student);
        $this->entityManager->flush();
    }
}