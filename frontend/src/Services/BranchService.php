<?php

namespace App\Services;

use App\Entity\Branch;
use Doctrine\ORM\EntityManagerInterface;

class BranchService
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function getAllBranches(): array
    {
        return $this->entityManager->getRepository(Branch::class)->findAll();
    }
    public function getBranch(Branch $branch): ?Branch
    {
        return $this->entityManager->getRepository(Branch::class)->find($branch);
    }

    public function getBranchByName(string $branch)
    {
        return $this->entityManager->getRepository(Branch::class)->findOneBy(['branch_name' => $branch]);
    }

}