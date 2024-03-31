<?php

namespace App\DataFixtures;

use App\Factory\AdminFactory;
use App\Factory\BranchFactory;
use App\Factory\CourseFactory;
use App\Factory\GroupFactory;
use App\Factory\LevelFactory;
use App\Factory\SemestreFactory;
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
        BranchFactory::createOne([
            'branch_name' => "computer engineering"
        ]);
        BranchFactory::createOne([
            'branch_name' => "industrial engineering"
        ]);
        BranchFactory::createOne([
            'branch_name' => "IT and management engineer "
        ]);
        GroupFactory::createMany(5);
        SemestreFactory::createOne([
            'semester_name' => "First Semester"
        ]);
        SemestreFactory::createOne([
            'semester_name' => "Second Semester"
        ]);
    }
}
