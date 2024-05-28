<?php

namespace App\Repository;

use App\Entity\Teacher;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Teacher>
 *
 * @method Teacher|null find($id, $lockMode = null, $lockVersion = null)
 * @method Teacher|null findOneBy(array $criteria, array $orderBy = null)
 * @method Teacher[]    findAll()
 * @method Teacher[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TeacherRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Teacher::class);
    }
    public function countStudentsByTeacher(Teacher $teacher): int
    {
        return $this->createQueryBuilder("t")
            ->select('COUNT(s.id)')
            ->leftJoin('t.students', 's')
            ->andWhere('t = :teacher')
            ->setParameter('teacher', $teacher)
            ->getQuery()
            ->getSingleScalarResult();
    }
    public function getCoursesByTeacher(Teacher $teacher): array
    {
        return array_column($this->createQueryBuilder("t")
            ->select("c.course_name AS course_name")
            ->leftJoin("t.courses", "c")
            ->andWhere("t = :teacher")
            ->setParameter("teacher", $teacher)
            ->getQuery()
            ->getScalarResult(), 'course_name');
    }
//    /**
//     * @return Teacher[] Returns an array of Teacher objects
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

//    public function findOneBySomeField($value): ?Teacher
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
