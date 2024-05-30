<?php

namespace App\Services;

use App\Entity\AcademicYear;
use App\Entity\Branch;
use App\Entity\Group;
use App\Entity\Teacher;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraints\Collection;

class GroupService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private AcademicYearService $academicYearService,
        private BranchService $branchService
    )
    {
    }

    public function getAllGroups(): array
    {
        return $this->entityManager->getRepository(Group::class)->findAll();
    }
    public function getGroupById(int $id): Group
    {
        return $this->entityManager->getRepository(Group::class)->find($id);
    }
    public function getGroupsByBranchAndYear(string $yearName, string $branchName): array
    {
        $branchId = $this->entityManager->getRepository(Branch::class)->findOneBy(['branch_name' => $branchName])->getId();
        $yearId = $this->entityManager->getRepository(AcademicYear::class)->findOneBy(['year' => $yearName])->getId();

        return $this->entityManager->getRepository(Group::class)->getGroupsByBranchAndYear($yearId, $branchId);
    }
    public function addStudents(Collection $students): static
    {
        $this->entityManager->getRepository(Group::class)->addStudents($students);
        return $this;
    }

    public function countGroups(): int
    {

        return $this->entityManager->getRepository(Group::class)->count();
    }

    public function saveGroup(Group $group): void
    {
        $this->entityManager->persist($group);
        $this->entityManager->flush();
    }

    public function createGroup(string $groupName, AcademicYear $academicYear, Branch $branch): ?Group
    {
        $group = new Group();
        $group->setGroupName($groupName);
        $group->addAcademicYear($academicYear);
        $group->addBranch($branch);
        $this->saveGroup($group);
        return $group;
    }
    public function groupsExists(): ?bool
    {
        if ($this->countGroups() > 0){
            return true;
        }
        return false;
    }
    public function calculateNumberOfGroups($students,int $numberOfStudentsPerGroup): int
    {
        return ceil(count($students)/$numberOfStudentsPerGroup);
    }

    /*
     * Explaining this function :
     * It calculates the number of groups needed based on the total number of students per AcademicYear and branch
     * So basically it calculates for each year how many groups needed and then return the max number of groups
     * and yes that's it (: Anaa Matara9
     * */
    public function calculateNumberOfGroupsNeededPerAcademicYearAndBranch(array $StudentsByAcademicYearAndBranch, int $numberOfStudentsPerGroup): array
    {
        $numberOfGroupsPerAcademicYearAndBranch = [];
        foreach ($StudentsByAcademicYearAndBranch as $year => $branches)
        {
            foreach ($branches as $branch => $students)
            {
                $numberOfGroupsPerAcademicYearAndBranch[$year][$branch] = $this->calculateNumberOfGroups($students,$numberOfStudentsPerGroup);
            }
        };

        return $numberOfGroupsPerAcademicYearAndBranch;
    }

    public function groupIsSaturated(int $maxNumberOfStudents, Group $group): bool
    {
        $studentsCountPerGroup = $this->entityManager->getRepository(Group::class)->countStudentsPerGroup($group);
        return $studentsCountPerGroup["studentCount"] >= $maxNumberOfStudents;
    }
    public function generateGroups(array $StudentsByAcademicYearAndBranch, int $numberOfStudentsPerGroup): void
    {
        $numberOfGroupsPerAcademicYearAndBranch = $this->calculateNumberOfGroupsNeededPerAcademicYearAndBranch($StudentsByAcademicYearAndBranch, $numberOfStudentsPerGroup);

        $uppercaseLetters = range('A', 'Z');
        foreach ($numberOfGroupsPerAcademicYearAndBranch as $year => $branches)
        {
            foreach ($branches as $branch => $numberOfGroups)
            {
                for ($i = 0; $i < $numberOfGroups; $i++) {
                    $academicYear = $this->academicYearService->getAcademicYearByYear($year);
                    $branchEntity = $this->branchService->getBranchByName($branch);
                    $group = $this->createGroup($uppercaseLetters[$i], $academicYear, $branchEntity);
                    foreach ($branchEntity->getCourses() as $course) {
                        $teacher = $course->getTeacher();
                        $teacher->addGroup($group);
                    }
                }
            }
        }

    }

    public function getGroupsByYearAndBranch(AcademicYear $year, Branch $branch): array
    {
        return $this->entityManager->getRepository(Group::class)->getGroupsByYearAndBranch($year, $branch);
    }

    public function countNumberOfGroups(AcademicYear $year, Branch $branch): int
    {
        return $this->entityManager->getRepository(Group::class)->countNumberOfGroups($year, $branch);
    }

    public function getGroupsOfTeacher(Teacher $teacher): array
    {
        $data = [];
        $groups = $this->entityManager->getRepository(Group::class)->getGroupsOfTeacher($teacher);

        foreach ($groups as $group) {
            foreach ($group->getAcademicYear() as $year) {
                foreach ($group->getBranches() as $branch) {
                    $data[$year->getYear()][$branch->getBranchName()][] = $group;
                }
            }
        }

        return $data;
    }


}