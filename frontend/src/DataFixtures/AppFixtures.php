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
        SemesterFactory::createOne([
                'semester_name' => "First Semester"
        ]);
        SemesterFactory::createOne([
                'semester_name' => "Second Semester"
        ]);
        // Create Admin User
        AdminFactory::createOne([
            "username" => "abdo",
        ]);

        // Create Teachers
        $t1 = TeacherFactory::createOne([
            "username" => "hanae",
            'roles' => ["ROLE_TEACHER"]
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
            "username" => "teacher5",
            'roles' => ["ROLE_TEACHER"]
        ]);
        $t6 = TeacherFactory::createOne([
            "username" => "teacher6",
            'roles' => ["ROLE_TEACHER"]
        ]);

        $t7 = TeacherFactory::createOne([
            "username" => "teacher7",
            'roles' => ["ROLE_TEACHER"]
        ]);
        $t8 = TeacherFactory::createOne([
            "username" => "teacher8",
            'roles' => ["ROLE_TEACHER"]
        ]);

        // Create Courses
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
            "teacher" => $t6
        ]);

        $databaseCourse = CourseFactory::createOne([
            "course_name" => "Database Systems",
            "teacher" => $t7
        ]);

        $architectureCourse = CourseFactory::createOne([
            "course_name" => "Computer Architecture",
            "teacher" => $t8
        ]);

        $branch1 = BranchFactory::createOne([
            'branch_name' => "Computer Engineering",
            "courses" => [
                $phpCourse,
                $networkingCourse,
                $databaseCourse,
                $architectureCourse
            ],
        ]);

        $branch2 = BranchFactory::createOne([
            'branch_name' => "Industrial Engineering",
            "courses" => [
                $aspCourse,
                $javaCourse,
                $networkingCourse
            ],
        ]);

        $branch3 = BranchFactory::createOne([
            'branch_name' => "IT and Management Engineering",
            "courses" => [
                $phpCourse,
                $devopsCourse,
                $databaseCourse
            ],
        ]);


        // Create Academic Years
        $firstYear = AcademicYearsFactory::createOne([
            'year' => AcademicYearEnum::FIRST_YEAR,
            'slug' => $this->slugger->slug(AcademicYearEnum::FIRST_YEAR),
            'branches' => [$branch1, $branch2]
        ]);

        $secondYear = AcademicYearsFactory::createOne([
            'year' => AcademicYearEnum::SECOND_YEAR,
            'slug' => $this->slugger->slug(AcademicYearEnum::SECOND_YEAR),
            'branches' => [$branch2]
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

        // Create Students
        StudentFactory::createMany(70, [
            'academicYear' => $firstYear,
            'branch' => $branch1
        ]);

        StudentFactory::createMany(70, [
            'academicYear' => $firstYear,
            'branch' => $branch2
        ]);

        StudentFactory::createMany(60, [
            'academicYear' => $secondYear,
            'branch' => $branch2
        ]);

        StudentFactory::createMany(50, [
            'academicYear' => $thirdYear,
            'branch' => $branch1
        ]);

        StudentFactory::createMany(60, [
            'academicYear' => $fourthYear,
            'branch' => $branch2
        ]);

        StudentFactory::createMany(83, [
            'academicYear' => $fifthYear,
            'branch' => $branch1
        ]);

        StudentFactory::createMany(53, [
            'academicYear' => $fifthYear,
            'branch' => $branch2
        ]);

        // Create Classrooms
        ClassroomFactory::createMany(20);
    }
}
