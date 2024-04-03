<?php

namespace App\Services\GroupServices;

use App\Entity\Group;
use App\Services\BranchService;
use App\Services\userServices\StudentService;
use Doctrine\ORM\EntityManagerInterface;

class GroupService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    )
    {
    }

    public function countGroups(): int
    {
        return $this->entityManager->getRepository(Group::class)->count();
    }
    public function createGroup(Group $group):void
    {
        $this->entityManager->persist($group);
        $this->entityManager->flush();
    }
    public function groupsExists(): ?bool
    {
        if ($this->countGroups() > 0){
            return true;
        }
        return false;
    }

    /*
     * Explaining this function :
     * It calculates the number of groups needed based on the total number of students per AcademicYear
     * So basically it calculates for each year how many groups needed and then return the max number of groups
     * and yes that's it (: Anaa Matara9
     * */
    public function calculateNumberOfGroupsNeeded(array $studentsByAcademicYear, int $numberOfStudentsPerGroup): int
    {
        $numberOfGroupsPerAcademicYear = [];
        foreach ($studentsByAcademicYear as $year => $value)
        {
            $numberOfGroupsPerAcademicYear[$year] = count($value)/$numberOfStudentsPerGroup;
        };

        return max($numberOfGroupsPerAcademicYear);
    }

    public function generateGroups(array $studentsByAcademicYear, int $numberOfStudentsPerGroup): void
    {
        $numberOfGroups = $this->calculateNumberOfGroupsNeeded($studentsByAcademicYear, $numberOfStudentsPerGroup);

        $uppercaseLetters = range('A', 'Z');
        foreach ($studentsByAcademicYear as $year => $students)
        {
            for ($i = 0; $i < $numberOfGroups; $i++) {
                $groupName = $year." ".$students[0]->getBranch()->getBranchName()." Group ".$uppercaseLetters[$i];
                $group = new Group();
                $group->setGroupName($groupName);
                $this->createGroup($group);
            }
        }

    }
}