<?php

namespace App\DataFixtures;

use App\Enums\AcademicYearEnum;
use App\Factory\AcademicYearsFactory;
use App\Factory\AdminFactory;
use App\Factory\BranchFactory;
use App\Factory\CourseFactory;
use App\Factory\GroupFactory;
use App\Factory\LevelFactory;
use App\Factory\SemesterFactory;
use App\Factory\StudentFactory;
use App\Factory\TeacherFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        AdminFactory::createOne([
            "username" => "abdo",
        ]);
        TeacherFactory::createOne([
            "username" => "hanae",
        ]);
        $branch1 = BranchFactory::createOne([
            'branch_name' => "computer engineering"
        ]);
        $branch2 = BranchFactory::createOne([
            'branch_name' => "industrial engineering"
        ]);
        $branch3 = BranchFactory::createOne([
            'branch_name' => "IT and management engineer "
        ]);
        SemesterFactory::createOne([
            'semester_name' => "First Semester"
        ]);
        SemesterFactory::createOne([
            'semester_name' => "Second Semester"
        ]);
        $firstYear = AcademicYearsFactory::createOne([
            'year' => AcademicYearEnum::FIRST_YEAR
        ]);
        $secondYear = AcademicYearsFactory::createOne([
            'year' => AcademicYearEnum::SECOND_YEAR
        ]);
        $thirdYear = AcademicYearsFactory::createOne([
            'year' => AcademicYearEnum::THIRD_YEAR
        ]);
        $fourthYear = AcademicYearsFactory::createOne([
            'year' => AcademicYearEnum::FOURTH_YEAR
        ]);
        $fifthYear = AcademicYearsFactory::createOne([
            'year' => AcademicYearEnum::FIFTH_YEAR
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

    }
}
