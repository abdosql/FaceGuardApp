<?php

namespace App\Services\userServices;

use App\Entity\Branch;
use App\Entity\Teacher;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class TeacherService extends UserService
{
    public function __construct(
        EntityManagerInterface $entityManager,
        SluggerInterface $slugger,
        UserPasswordHasherInterface $passwordHasher
    )
    {
        parent::__construct($entityManager, $slugger, $passwordHasher);
    }

    public function getAllTeachers(): array
    {
        return $this->entityManager->getRepository(Teacher::class)->findAll();
    }

    public function getTeacherById(Teacher $teacher): ?Teacher
    {
        return $this->entityManager->getRepository(Teacher::class)->find($teacher);
    }

    public function saveTeacher(Teacher $teacher, $form): array
    {
        $selectedBranches = $form->get('branches')->getData();
        foreach ($selectedBranches as $branch) {
            $teacher->addBranch($branch);
        }
        $password = $this->generateRandomPassword($teacher);
        $teacher->setUsername(
            $this->generateUniqueUsername(
                    $teacher->getFirstName(),
                    $teacher->getLastName())
        );
        $teacher->setPassword($password["hashedPassword"]);
        $teacher->setRoles(["ROLE_TEACHER"]);
        $teacher->setDtype("teacher");

        $data = [
            "username" => $teacher->getUsername(),
            "password" => $password["password"]
        ];
        $this->entityManager->persist($teacher);
        $this->entityManager->flush();
        return $data;
    }

    public function countStudentsByTeacher(Teacher $teacher): int
    {
        return $this->entityManager->getRepository(Teacher::class)->countStudentsByTeacher($teacher);
    }
    public function deleteTeacher(Teacher $teacher): void
    {
        $this->entityManager->remove($teacher);
        $this->entityManager->flush();
    }
    



}