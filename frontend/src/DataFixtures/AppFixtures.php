<?php

namespace App\DataFixtures;

use App\Enums\AcademicYearEnum;
use App\Factory\AcademicYearsFactory;
use App\Factory\AdminFactory;
use App\Factory\BranchFactory;
use App\Factory\ClassroomFactory;
use App\Factory\CourseFactory;
use App\Factory\GroupFactory;
use App\Factory\LevelFactory;
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
        AdminFactory::createOne([
            "username" => "abdo",
        ]);
        $t1 = TeacherFactory::createOne([
            "username" => "hanae",
        ]);
        $t2 = TeacherFactory::createOne([
            "username" => "teacher1",
            'roles' => ["ROLE_TEACHER"]
        ]);
        $t3 = TeacherFactory::createOne([
            "username" => "teacher2",
            'roles' => ["ROLE_TEACHER"]
        ]);
        $t4 = TeacherFactory::createOne([
            "username" => "teacher3",
            'roles' => ["ROLE_TEACHER"]
        ]);
        $t5 = TeacherFactory::createOne([
            "username" => "teacher4",
            'roles' => ["ROLE_TEACHER"]
        ]);
        $aspCourse = CourseFactory::createOne([
            "course_name" => "ASP",
            "teacher" => $t1
        ]);

        $phpCourse = CourseFactory::createOne([
            "course_name" => "PHP",
            "teacher" => $t2
        ]);

        $devopsCourse = CourseFactory::createOne([
            "course_name" => "DEVOPS",
            "teacher" => $t3
        ]);

        $symfonyCourse = CourseFactory::createOne([
            "course_name" => "Symfony",
            "teacher" => $t4
        ]);

        $javaCourse = CourseFactory::createOne([
            "course_name" => "JAVA",
            "teacher" => $t5
        ]);
        $networkingCourse = CourseFactory::createOne([
            "course_name" => "Networking",
            "teacher" => $t2 // Assuming $t6 is the teacher for Networking course
        ]);

        $databaseCourse = CourseFactory::createOne([
            "course_name" => "Database Systems",
            "teacher" => $t4 // Assuming $t7 is the teacher for Database Systems course
        ]);

        $architectureCourse = CourseFactory::createOne([
            "course_name" => "Computer Architecture",
            "teacher" => $t3 // Assuming $t8 is the teacher for Computer Architecture course
        ]);

        $branch1 = BranchFactory::createOne([
            'branch_name' => "computer engineering",
            "courses" => [$phpCourse, $networkingCourse, $databaseCourse, $architectureCourse],
        ]);

        $branch2 = BranchFactory::createOne([
            'branch_name' => "industrial engineering",
            "courses" => [$aspCourse, $javaCourse, $networkingCourse],
        ]);

        $branch3 = BranchFactory::createOne([
            'branch_name' => "IT and management engineer",
            "courses" => [$phpCourse, $devopsCourse, $databaseCourse],
        ]);
        SemesterFactory::createOne([
            'semester_name' => "First Semester"
        ]);
        SemesterFactory::createOne([
            'semester_name' => "Second Semester"
        ]);
        $firstYear = AcademicYearsFactory::createOne([
            'year' => AcademicYearEnum::FIRST_YEAR,
            'slug' => $this->slugger->slug(AcademicYearEnum::FIRST_YEAR)
        ]);
        $secondYear = AcademicYearsFactory::createOne([
            'year' => AcademicYearEnum::SECOND_YEAR,
            'slug' => $this->slugger->slug(AcademicYearEnum::SECOND_YEAR)
        ]);
        $thirdYear = AcademicYearsFactory::createOne([
            'year' => AcademicYearEnum::THIRD_YEAR,
            'slug' => $this->slugger->slug(AcademicYearEnum::THIRD_YEAR),
            'branches' => [$branch1, $branch2, $branch3]

        ]);
        $fourthYear = AcademicYearsFactory::createOne([
            'year' => AcademicYearEnum::FOURTH_YEAR,
            'slug' => $this->slugger->slug(AcademicYearEnum::FOURTH_YEAR),
            'branches' => [$branch1, $branch2, $branch3]

        ]);
        $fifthYear = AcademicYearsFactory::createOne([
            'year' => AcademicYearEnum::FIFTH_YEAR,
            'slug' => $this->slugger->slug(AcademicYearEnum::FIFTH_YEAR),
            'branches' => [$branch1, $branch2, $branch3]
        ]);
        StudentFactory::createMany(143,[
            'academicYear' => $firstYear,
            'branch' => $branch1
        ]);
        StudentFactory::createMany(120,[
            'academicYear' => $firstYear,
            'branch' => $branch2
        ]);
        StudentFactory::createMany(160,[
            'academicYear' => $secondYear,
            'branch' => $branch2
        ]);
        StudentFactory::createMany(95,[
            'academicYear' => $thirdYear,
            'branch' => $branch1
        ]);
        StudentFactory::createMany(90,[
            'academicYear' => $fourthYear,
            'branch' => $branch2
        ]);
        StudentFactory::createMany(83,[
            'academicYear' => $fifthYear,
            'branch' => $branch1
        ]);
        StudentFactory::createMany(53,[
            'academicYear' => $fifthYear,
            'branch' => $branch2
        ]);


        //classrooms
        ClassroomFactory::createMany(20);
    }
}
