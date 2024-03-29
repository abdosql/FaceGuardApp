<?php

namespace App\DataFixtures;

use App\Factory\AdminFactory;
use App\Factory\CourseFactory;
use App\Factory\GroupFactory;
use App\Factory\LevelFactory;
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
        LevelFactory::createMany(10);
        GroupFactory::createMany(10);
    }
}
