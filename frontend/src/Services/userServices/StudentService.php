<?php

namespace App\Services\userServices;

use App\Entity\Group;
use App\Entity\Student;
use App\Services\AcademicYearService;
use App\Services\BranchService;
use App\Services\GroupService;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Validator\Constraints\Collection;

class StudentService extends UserService
{
    public function __construct(
        EntityManagerInterface $entityManager,
        SluggerInterface $slugger,
        UserPasswordHasherInterface $passwordHasher,
        private GroupService $groupService,
        private AcademicYearService $academicYearService,
        private BranchService $branchService

    )
    {
        parent::__construct($entityManager, $slugger, $passwordHasher);
    }

    public function getAllStudents(): array
    {
        return $this->entityManager->getRepository(Student::class)->findAll();
    }
    public function getStudentsCount(): int
    {
        return $this->entityManager->getRepository(Student::class)->count();
    }
    public function getStudentById(Student $student): ?Student
    {
        return $this->entityManager->getRepository(Student::class)->find($student);
    }

    public function countStudentsPercentageByGender(): array
    {
        $genders = $this->entityManager->getRepository(Student::class)->countStudentsByGender();
        $total = $genders[0]["gender_count"] + $genders[1]["gender_count"];
        return [
            "Female" => round(($genders[0]["gender_count"]/$total)*100, 2),
            "Male" => round(($genders[1]["gender_count"]/$total)*100, 2),
        ];

    }
    public function saveStudent(Student $student): array
    {
        $password = $this->generateRandomPassword($student);
        $student->setUsername(
            $this->generateUniqueUsername(
                $student->getFirstName(),
                $student->getLastName())
        );
        $student->setPassword($password["hashedPassword"]);
        $student->setRoles(["ROLE_STUDENT"]);
        $student->setDtype("student");

        $data = [
            "username" => $student->getUsername(),
            "password" => $password["password"]
        ];
        $this->entityManager->persist($student);
        $this->entityManager->flush();
        return $data;
    }
    public function deleteStudent(student $student): void
    {
        $this->entityManager->remove($student);
        $this->entityManager->flush();
    }

    public function getStudentsByAcademicYearAndBranch(): array
    {
        return $this->entityManager->getRepository(Student::class)->getStudentsByAcademicYearAndBranch();
    }
    public function studentsWithoutGroupExist(): array
    {
        $students = $this->entityManager->getRepository(Student::class)->countStudentsWithoutGroup();
        if ($students > 0){
            if ($this->getStudentsCount() != $students){
                return [
                    'status' => false,
                    "message" => "There are some students without groups. Sync them now.",
                    'button' => "Sync"
                ];
            }
            return [
                'status' => true,
            ];
        }
        return [
            'status' => false,
            "message" => "You haven't generated groups yet. Generate them now.",
            'button' => "Generate"

        ];
    }
    public function AssignStudentsToCovenantGroupRandomly(int $studentsNumberPerGroup): void
    {
        if(!$this->groupService->groupsExists())
        {
            $studentsByAcademicYearAndBranch = $this->getStudentsByAcademicYearAndBranch();
            $this->groupService->generateGroups($studentsByAcademicYearAndBranch, $studentsNumberPerGroup);
            foreach ($studentsByAcademicYearAndBranch as $year => $branches)
            {
                foreach ($branches as $branch => $students)
                {
                    $groups = $this->groupService->getGroupsByBranchAndYear($year, $branch);
                    $groupsNumber = $this->groupService->calculateNumberOfGroups($students, $studentsNumberPerGroup);
                    $studentsChunked = array_chunk($students, $studentsNumberPerGroup);

                    for ($i = 0; $i < $groupsNumber; $i++)
                    {
                        $studentsChunkCollection = new ArrayCollection($studentsChunked[$i]);
                        $groups[$i]->addStudents($studentsChunkCollection);
                    }
                }
                $this->entityManager->flush();
            }
        }else{
            $this->syncNewStudentsToCovenantGroup($studentsNumberPerGroup);
        }

    }

    public function syncNewStudentsToCovenantGroup(int $maxNumberOfStudents): void
    {

            $studentsWithoutGroup = $this->entityManager->getRepository(Student::class)->getStudentsWithoutGroup();
            foreach ($studentsWithoutGroup as $year => $branches)
            {
                foreach ($branches as $branch => $students)
                {
                    $groups = $this->groupService->getGroupsByBranchAndYear($year, $branch);
                    $latestGroup = $groups[count($groups) - 1];
                    $groupLetter = $latestGroup->getGroupName();
                    foreach ($students as $student){
                        if ($this->groupService->groupIsSaturated($maxNumberOfStudents, $latestGroup)){
                            $uppercaseLetters = range($groupLetter, 'Z');
                            $academicYear = $this->academicYearService->getAcademicYearByYear($year);
                            $branchEntity = $this->branchService->getBranchByName($branch);
                            $latestGroup = $this->groupService->createGroup($uppercaseLetters[1], $academicYear, $branchEntity);
                        }
                        $latestGroup->addStudent($student);
                        $this->entityManager->flush();
                    }
                }
            }
        }
}