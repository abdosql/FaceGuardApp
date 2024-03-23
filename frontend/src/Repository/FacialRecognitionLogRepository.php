<?php

namespace App\Repository;

use App\Entity\FacialRecognitionLog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FacialRecognitionLog>
 *
 * @method FacialRecognitionLog|null find($id, $lockMode = null, $lockVersion = null)
 * @method FacialRecognitionLog|null findOneBy(array $criteria, array $orderBy = null)
 * @method FacialRecognitionLog[]    findAll()
 * @method FacialRecognitionLog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FacialRecognitionLogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FacialRecognitionLog::class);
    }

    //    /**
    //     * @return FacialRecognitionLog[] Returns an array of FacialRecognitionLog objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('f')
    //            ->andWhere('f.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('f.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?FacialRecognitionLog
    //    {
    //        return $this->createQueryBuilder('f')
    //            ->andWhere('f.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
