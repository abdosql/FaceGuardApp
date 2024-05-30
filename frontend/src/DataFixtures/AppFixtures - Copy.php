<?php

namespace App\DataFixtures;

use App\Enums\AcademicYearEnum;
use App\Factory\AcademicYearsFactory;
use App\Factory\AdminFactory;
use App\Factory\BranchFactory;
use App\Factory\ClassroomFactory;
use App\Factory\CourseFactory;
use App\Factory\SemesterFactory;
use App\Factory\StudentFactory;
use App\Factory\TeacherFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;

class AppFixtures extends Fixture
{
    public function __construct(private SluggerInterface $slugger)
    {
    }

    public function load(ObjectManager $manager): void
    {
        // Create Semesters
        $semester1 = SemesterFactory::createOne([
            'semester_name' => "First Semester"
        ]);
        $semester2 = SemesterFactory::createOne([
            'semester_name' => "Second Semester"
        ]);

        // Create Admin User
        AdminFactory::createOne([
            "username" => "abdo",
            'first_name' => "Abdelaziz",
            'last_name' => "Saqqal",
            'image_name' => "1706396409942-6624ed416023f712150858.jpeg"
        ]);

        // Create Teachers
        $teachers = [];
        for ($i = 1; $i <= 52; $i++) {
            $teachers[] = TeacherFactory::createOne(["username" => "teacher$i", 'roles' => ["ROLE_TEACHER"]]);
        }
        $genieInformatiqueCourses = [
            CourseFactory::createOne(["course_name" => "Algorithmique", "teacher" => $teachers[0], "semesters" => [$semester1]]),
            CourseFactory::createOne(["course_name" => "Programmation", "teacher" => $teachers[1], "semesters" => [$semester1]]),
            CourseFactory::createOne(["course_name" => "Mathématiques", "teacher" => $teachers[2], "semesters" => [$semester1]]),
            CourseFactory::createOne(["course_name" => "Physique", "teacher" => $teachers[3], "semesters" => [$semester1]]),
            CourseFactory::createOne(["course_name" => "Anglais", "teacher" => $teachers[4], "semesters" => [$semester1]]),
            CourseFactory::createOne(["course_name" => "Génie Logiciel", "teacher" => $teachers[9], "semesters" => [$semester1]]),
            CourseFactory::createOne(["course_name" => "Systèmes d'exploitation", "teacher" => $teachers[10], "semesters" => [$semester1]]),
            // Semester 2
            CourseFactory::createOne(["course_name" => "Bases de données", "teacher" => $teachers[5], "semesters" => [$semester2]]),
            CourseFactory::createOne(["course_name" => "Réseaux", "teacher" => $teachers[6], "semesters" => [$semester2]]),
            CourseFactory::createOne(["course_name" => "Électronique", "teacher" => $teachers[7], "semesters" => [$semester2]]),
            CourseFactory::createOne(["course_name" => "Projet", "teacher" => $teachers[8], "semesters" => [$semester2]]),
            CourseFactory::createOne(["course_name" => "Programmation Web", "teacher" => $teachers[11], "semesters" => [$semester2]]),
            CourseFactory::createOne(["course_name" => "Sécurité Informatique", "teacher" => $teachers[12], "semesters" => [$semester2]]),
            CourseFactory::createOne(["course_name" => "Intelligence Artificielle", "teacher" => $teachers[13], "semesters" => [$semester2]]),
        ];

        $genieIndustrielCourses = [
            // Semester 1
            CourseFactory::createOne(["course_name" => "Mathématiques Appliquées", "teacher" => $teachers[22], "semesters" => [$semester1]]),
            CourseFactory::createOne(["course_name" => "Physique Générale", "teacher" => $teachers[23], "semesters" => [$semester1]]),
            CourseFactory::createOne(["course_name" => "Chimie Industrielle", "teacher" => $teachers[24], "semesters" => [$semester1]]),
            CourseFactory::createOne(["course_name" => "Dessin Industriel", "teacher" => $teachers[25], "semesters" => [$semester1]]),
            CourseFactory::createOne(["course_name" => "Anglais Technique", "teacher" => $teachers[26], "semesters" => [$semester1]]),
            CourseFactory::createOne(["course_name" => "Gestion de la Production", "teacher" => $teachers[31], "semesters" => [$semester1]]),
            CourseFactory::createOne(["course_name" => "Contrôle de Qualité", "teacher" => $teachers[32], "semesters" => [$semester1]]),
            // Semester 2
            CourseFactory::createOne(["course_name" => "Mécanique des Solides", "teacher" => $teachers[27], "semesters" => [$semester2]]),
            CourseFactory::createOne(["course_name" => "Électricité Industrielle", "teacher" => $teachers[28], "semesters" => [$semester2]]),
            CourseFactory::createOne(["course_name" => "Automatisation des Systèmes", "teacher" => $teachers[29], "semesters" => [$semester2]]),
            CourseFactory::createOne(["course_name" => "Programmation Industrielle", "teacher" => $teachers[30], "semesters" => [$semester2]]),
            CourseFactory::createOne(["course_name" => "Gestion des Ressources Humaines", "teacher" => $teachers[34], "semesters" => [$semester2]]),
            CourseFactory::createOne(["course_name" => "Sécurité Industrielle", "teacher" => $teachers[35], "semesters" => [$semester2]]),
            CourseFactory::createOne(["course_name" => "Maintenance Industrielle", "teacher" => $teachers[37], "semesters" => [$semester2]]),
        ];

        $genieMecaniqueCourses = [
            // Semester 1
            CourseFactory::createOne(["course_name" => "Mécanique des Fluides", "teacher" => $teachers[42], "semesters" => [$semester1]]),
            CourseFactory::createOne(["course_name" => "Résistance des Matériaux", "teacher" => $teachers[43], "semesters" => [$semester1]]),
            CourseFactory::createOne(["course_name" => "Thermodynamique", "teacher" => $teachers[44], "semesters" => [$semester1]]),
            CourseFactory::createOne(["course_name" => "Matériaux de Construction", "teacher" => $teachers[48], "semesters" => [$semester1]]),
            CourseFactory::createOne(["course_name" => "Gestion de la Maintenance", "teacher" => $teachers[51], "semesters" => [$semester1]]),
            CourseFactory::createOne(["course_name" => "Ingénierie des Systèmes", "teacher" => $teachers[40], "semesters" => [$semester1]]),
            CourseFactory::createOne(["course_name" => "Lean Manufacturing", "teacher" => $teachers[39], "semesters" => [$semester1]]),
            // Semester 2
            CourseFactory::createOne(["course_name" => "Conception Assistée par Ordinateur (CAO)", "teacher" => $teachers[45], "semesters" => [$semester2]]),
            CourseFactory::createOne(["course_name" => "Systèmes de Production", "teacher" => $teachers[46], "semesters" => [$semester2]]),
            CourseFactory::createOne(["course_name" => "Mécanique des Structures", "teacher" => $teachers[47], "semesters" => [$semester2]]),
            CourseFactory::createOne(["course_name" => "Énergies Renouvelables", "teacher" => $teachers[49], "semesters" => [$semester2]]),
            CourseFactory::createOne(["course_name" => "Automatisation Industrielle", "teacher" => $teachers[50], "semesters" => [$semester2]]),
            CourseFactory::createOne(["course_name" => "Modélisation et Simulation", "teacher" => $teachers[41], "semesters" => [$semester2]]),
            CourseFactory::createOne(["course_name" => "Sécurité Informatique", "teacher" => $teachers[12], "semesters" => [$semester2]]),
        ];


        // Create Branches
        $genieBranch = BranchFactory::createOne(["branch_name" => "Génie Informatique", "courses" => $genieInformatiqueCourses]);
        $genieIndustrielBranch = BranchFactory::createOne(["branch_name" => "Génie Industriel", "courses" => $genieIndustrielCourses]);
        $genieMecanicsBranch = BranchFactory::createOne(["branch_name" => "Génie Mécaniques", "courses" => $genieMecaniqueCourses]);

        $firstYear = AcademicYearsFactory::createOne([
            'year' => AcademicYearEnum::FIRST_YEAR,
            'slug' => $this->slugger->slug(AcademicYearEnum::FIRST_YEAR),
            'branches' => [$genieBranch, $genieIndustrielBranch],
            'semesters' => [$semester1, $semester2]
        ]);
        $secondYear = AcademicYearsFactory::createOne([
            'year' => AcademicYearEnum::SECOND_YEAR,
            'slug' => $this->slugger->slug(AcademicYearEnum::SECOND_YEAR),
            'branches' => [$genieBranch, $genieIndustrielBranch, $genieMecanicsBranch], // Corrected line
            'semesters' => [$semester1, $semester2]
        ]);
        $thirdYear = AcademicYearsFactory::createOne([
            'year' => AcademicYearEnum::THIRD_YEAR,
            'slug' => $this->slugger->slug(AcademicYearEnum::THIRD_YEAR),
            'branches' => [$genieBranch, $genieIndustrielBranch, $genieMecanicsBranch],
            'semesters' => [$semester1, $semester2]
        ]);
        $forthYear = AcademicYearsFactory::createOne([
            'year' => AcademicYearEnum::FOURTH_YEAR,
            'slug' => $this->slugger->slug(AcademicYearEnum::FOURTH_YEAR),
            'branches' => [$genieBranch, $genieIndustrielBranch, $genieMecanicsBranch],
            'semesters' => [$semester1, $semester2]
        ]);
        $fifthYear = AcademicYearsFactory::createOne([
            'year' => AcademicYearEnum::FIFTH_YEAR,
            'slug' => $this->slugger->slug(AcademicYearEnum::FOURTH_YEAR),
            'branches' => [$genieBranch, $genieIndustrielBranch, $genieMecanicsBranch],
            'semesters' => [$semester1, $semester2]
        ]);


        StudentFactory::createMany(80, [
            'academicYear' => $firstYear,
            'branch' => $genieBranch
        ]);
        StudentFactory::createMany(80, [
            'academicYear' => $firstYear,
            'branch' => $genieIndustrielBranch
        ]);
        StudentFactory::createMany(80, [
            'academicYear' => $firstYear,
            'branch' => $genieMecanicsBranch
        ]);
        //
        StudentFactory::createMany(80, [
            'academicYear' => $secondYear,
            'branch' => $genieMecanicsBranch
        ]);
        StudentFactory::createMany(80, [
            'academicYear' => $secondYear,
            'branch' => $genieIndustrielBranch
        ]);
        StudentFactory::createMany(80, [
            'academicYear' => $secondYear,
            'branch' => $genieBranch
        ]);
        //
        StudentFactory::createMany(80, [
            'academicYear' => $forthYear,
            'branch' => $genieIndustrielBranch
        ]);
        StudentFactory::createMany(80, [
            'academicYear' => $forthYear,
            'branch' => $genieMecanicsBranch
        ]);
        StudentFactory::createMany(80, [
            'academicYear' => $forthYear,
            'branch' => $genieBranch
        ]);
        //
        StudentFactory::createMany(80, [
            'academicYear' => $thirdYear,
            'branch' => $genieBranch
        ]);
        StudentFactory::createMany(80, [
            'academicYear' => $thirdYear,
            'branch' => $genieMecanicsBranch
        ]);
        StudentFactory::createMany(80, [
            'academicYear' => $thirdYear,
            'branch' => $genieIndustrielBranch
        ]);


        // Create Classrooms
        ClassroomFactory::createMany(200);

        // Persist all changes
        $manager->flush();
    }
}