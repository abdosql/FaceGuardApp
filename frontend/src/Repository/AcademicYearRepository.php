<?php

namespace App\Repository;

use App\Entity\AcademicYear;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AcademicYear>
 *
 * @method AcademicYear|null find($id, $lockMode = null, $lockVersion = null)
 * @method AcademicYear|null findOneBy(array $criteria, array $orderBy = null)
 * @method AcademicYear[]    findAll()
 * @method AcademicYear[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AcademicYearRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AcademicYear::class);
    }

    public function getAcademicYearWithBranches(): array
    {
        return $this->createQueryBuilder('a')
            ->leftJoin('a.branches', 'b')
            ->getQuery()
            ->getResult();
    }
    public function getAcademicYearWithBranchesAndCountOfGroups(): array
    {
        return $this->createQueryBuilder('a')
            ->leftJoin('a.branches', 'b')
            ->leftJoin('b.groups', 'g')
            ->addSelect('b', 'COUNT(DISTINCT g.id) as groupsCount')
            ->groupBy('a.id, b.id')
            ->getQuery()
            ->getResult();
    }






    //    /**
    //     * @return AcademicYears[] Returns an array of AcademicYears objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('a.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?AcademicYears
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
