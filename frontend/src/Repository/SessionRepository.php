<?php

namespace App\Repository;

use App\Entity\Session;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Session>
 *
 * @method Session|null find($id, $lockMode = null, $lockVersion = null)
 * @method Session|null findOneBy(array $criteria, array $orderBy = null)
 * @method Session[]    findAll()
 * @method Session[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SessionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Session::class);
    }
    public function findTeacherSessions($teacherId): array
    {
        return $this->createQueryBuilder('s')
            ->leftJoin('s.course', 'c')
            ->where('c.teacher = :teacherId')
            ->setParameter('teacherId', $teacherId)
            ->getQuery()
            ->getResult();
    }
    public function findStudentSessions(int $studentId): array
    {
        return $this->createQueryBuilder('s')
            ->leftJoin('s.timeSchedule', 'ts')
            ->leftJoin('ts.group_', 'g')
            ->leftJoin('g.students', 'st')
            ->andWhere('st.id = :studentId')
            ->setParameter('studentId', $studentId)
            ->getQuery()
            ->getResult();
    }



    //    /**
    //     * @return Session[] Returns an array of Session objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('s.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Session
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
