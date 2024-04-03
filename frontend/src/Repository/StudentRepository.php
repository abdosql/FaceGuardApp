<?php

namespace App\Repository;

use App\Entity\Student;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Student>
 *
 * @method Student|null find($id, $lockMode = null, $lockVersion = null)
 * @method Student|null findOneBy(array $criteria, array $orderBy = null)
 * @method Student[]    findAll()
 * @method Student[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StudentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Student::class);
    }
    public function studentsWithoutGroup(): int
    {
        return $this->createQueryBuilder("s")
            ->select("COUNT(s)")
            ->leftJoin("s.group_", "g")
            ->andWhere("g IS NOT NULL")
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function getStudentsByAcademicYear(): array
    {
        $studentsByAcademicYear = [];

        // Retrieve students along with their academic years
        $students = $this->createQueryBuilder("s")
            ->leftJoin("s.academicYear", "ay")
            ->getQuery()
            ->getResult();

        // Organize students by academic year
        foreach ($students as $student) {
            $academicYear = $student->getAcademicYear()->getYear();
            $studentsByAcademicYear[$academicYear][] = $student;
        }

        return $studentsByAcademicYear;
    }
    //    /**
    //     * @return Student[] Returns an array of Student objects
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

    //    public function findOneBySomeField($value): ?Student
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
