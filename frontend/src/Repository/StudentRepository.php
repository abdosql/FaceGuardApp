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
    public function countStudentsWithoutGroup(): int
    {
        return $this->createQueryBuilder("s")
            ->select("COUNT(s)")
            ->leftJoin("s.group_", "g")
            ->andWhere("g IS NOT NULL")
            ->getQuery()
            ->getSingleScalarResult();
    }
    public function OrganizeStudentsByAcademicYearAndBranch(array $students): array
    {
        $studentsByAcademicYearAndBranch = [];

        // Organize students by academic year and branch
        foreach ($students as $student) {
            $academicYear = $student->getAcademicYear()->getYear();
            $branch = $student->getBranch()->getBranchName();
            $studentsByAcademicYearAndBranch[$academicYear][$branch][] = $student;
        }
        return $studentsByAcademicYearAndBranch;
    }
    public function getStudentsByAcademicYearAndBranch(): array
    {
        $students = $this->createQueryBuilder("s")
            ->select('s', 'ay', 'b')
            ->leftJoin("s.academicYear", "ay")
            ->leftJoin("s.branch", "b")
            ->getQuery()
            ->getResult();
        return $this->OrganizeStudentsByAcademicYearAndBranch($students);

    }
    public function getStudentsWithoutGroup(): array
    {
        $students = $this->createQueryBuilder("s")
            ->select('s', 'ay', 'b')
            ->leftJoin("s.academicYear", "ay")
            ->leftJoin("s.branch", "b")
            ->leftJoin("s.group_", "g")
            ->andWhere("g IS NULL")
            ->getQuery()
            ->getResult();
        return $this->OrganizeStudentsByAcademicYearAndBranch($students);
    }
    public function countStudentsByGender(): array
    {
        return $this->createQueryBuilder("s")
            ->select("s.gender, COUNT(s.gender) as gender_count")
            ->groupBy("s.gender")
            ->getQuery()
            ->getResult();
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
