<?php

namespace App\Repository;

use App\Entity\RFIDCard;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RFIDCard>
 *
 * @method RFIDCard|null find($id, $lockMode = null, $lockVersion = null)
 * @method RFIDCard|null findOneBy(array $criteria, array $orderBy = null)
 * @method RFIDCard[]    findAll()
 * @method RFIDCard[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RFIDCardRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RFIDCard::class);
    }

    //    /**
    //     * @return RFIDCard[] Returns an array of RFIDCard objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('r.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?RFIDCard
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
