<?php

namespace App\Repository;

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
