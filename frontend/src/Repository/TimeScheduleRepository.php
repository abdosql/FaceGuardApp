<?php

namespace App\Repository;

use App\Entity\TimeSchedule;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TimeSchedule>
 *
 * @method TimeSchedule|null find($id, $lockMode = null, $lockVersion = null)
 * @method TimeSchedule|null findOneBy(array $criteria, array $orderBy = null)
 * @method TimeSchedule[]    findAll()
 * @method TimeSchedule[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TimeScheduleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TimeSchedule::class);
    }

    public function findBySemesterAndGroup($semesterId, $groupId)
    {
        return $this->createQueryBuilder('ts')
            ->leftJoin('ts.sessions', 's')
            ->addSelect('s')
            ->leftJoin('s.course', 'c')
            ->addSelect('c')
            ->leftJoin('s.classroom', 'cl')
            ->addSelect('cl')
            ->where('ts.semester = :semesterId')
            ->andWhere('ts.group_ = :groupId')
            ->setParameter('semesterId', $semesterId)
            ->setParameter('groupId', $groupId)
            ->getQuery()
            ->getResult();
    }



    //    /**
    //     * @return TimeSchedule[] Returns an array of TimeSchedule objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('t.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?TimeSchedule
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
