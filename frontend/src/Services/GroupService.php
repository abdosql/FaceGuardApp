<?php

namespace App\Services;

use App\Entity\AcademicYear;
use App\Entity\Branch;
use App\Entity\Group;
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
    public function getGroupsLikeName($name): array
    {
        return $this->entityManager->getRepository(Group::class)->getGroupByName($name);
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

    public function explodedGroupName(): array
    {
        $explodedGroups = [];
        foreach ($this->getAllGroups() as $group) {
            $groupName = $group->getGroupName();
            $explodedGroup = explode(" Year", $groupName, 2);
            $year = trim($explodedGroup[0]);
            $text = trim($explodedGroup[1] ?? '');
            $yearKey = strtolower($year) . "-year";
            if (!isset($explodedGroups[$yearKey])) {
                $explodedGroups[$yearKey] = [];
            }
            $explodedGroups[$yearKey][] = $text;
        }

        // Transform the associative array to the desired format
        $result = [];
        foreach ($explodedGroups as $year => $groups) {
            $result[] = [
                "year" => $year,
                "groups" => $groups
            ];
        }

        return $result;
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
                    $this->createGroup($uppercaseLetters[$i], $academicYear, $branchEntity);
                }
            }
        }

    }
}