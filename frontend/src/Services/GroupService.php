<?php

namespace App\Services;

use App\Entity\Group;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraints\Collection;

class GroupService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    )
    {
    }
    public function getGroupsLikeName($name): array
    {
        return $this->entityManager->getRepository(Group::class)->getGroupByName($name);
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
    public function createGroup($group_name): Group
    {
        $group = new Group();
        $group->setGroupName($group_name);
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
                    $group_name = $year." ".$branch." ".$uppercaseLetters[$i];
                    $this->createGroup($group_name);
                }
            }
        }

    }
}