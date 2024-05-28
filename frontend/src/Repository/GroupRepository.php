<?php

namespace App\Repository;

use App\Entity\AcademicYear;
use App\Entity\Branch;
use App\Entity\Group;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Group>
 *
 * @method Group|null find($id, $lockMode = null, $lockVersion = null)
 * @method Group|null findOneBy(array $criteria, array $orderBy = null)
 * @method Group[]    findAll()
 * @method Group[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Group::class);
    }

    public function getGroupByName($name): array
    {
        return $this->createQueryBuilder("g")
            ->andWhere("g.group_name LIKE :name")
            ->setParameter("name", '%' . $name . '%')
            ->getQuery()
            ->getResult();
    }
    public function getGroupsByBranchAndYear(int $yearId, int $branchId): array
    {
        // Use the IDs to filter groups
        return $this->createQueryBuilder("g")
            ->leftJoin("g.branches", "branch")
            ->leftJoin("g.academicYear", "year")
            ->andWhere("branch.id = :branchId")
            ->andWhere("year.id = :yearId")
            ->setParameter("branchId", $branchId)
            ->setParameter("yearId", $yearId)
            ->getQuery()
            ->getResult();
    }
    public function getGroupsByYearAndBranch(AcademicYear $year, Branch $branch): array
    {
        return $this->createQueryBuilder('g')
            ->leftJoin('g.branches', 'branch')
            ->leftJoin('g.academicYear', 'year')
            ->leftJoin('g.students', 'student')
            ->addSelect('COUNT(student.id) as studentsCount')
            ->andWhere('year.id = :yearId')
            ->andWhere('branch.id = :branchId')
            ->setParameter('yearId', $year->getId())
            ->setParameter('branchId', $branch->getId())
            ->groupBy('g.id')
            ->getQuery()
            ->getResult();
    }
    public function countStudentsPerGroup(Group $group): array
    {
        return $this->createQueryBuilder("g")
            ->leftJoin("g.students", "s")
            ->andWhere("g = :group")
            ->setParameter("group", $group)
            ->select("g", "COUNT(s) AS studentCount")
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function countNumberOfGroups(AcademicYear $year, Branch $branch): int
    {
        return $this->createQueryBuilder("g")
            ->select("COUNT(g)")
            ->leftJoin("g.academicYear", "year")
            ->leftJoin("g.branches", "branch")
            ->andWhere("year.id = :yearId")
            ->andWhere("branch.id = :branchId")
            ->setParameter("yearId", $year)
            ->setParameter("branchId", $branch)
            ->getQuery()
            ->getSingleScalarResult();
    }
    //    /**
    //     * @return Group[] Returns an array of Group objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('g')
    //            ->andWhere('g.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('g.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Group
    //    {
    //        return $this->createQueryBuilder('g')
    //            ->andWhere('g.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
