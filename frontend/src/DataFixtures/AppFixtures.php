<?php

namespace App\DataFixtures;

use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        UserFactory::createOne([
            "username" => "abdo",
        ]);
        UserFactory::createOne([
            "username" => "hanae",
            "roles" => ["ROLE_TEACHER"]
        ]);
    }
}
