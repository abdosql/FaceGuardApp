<?php

namespace App\Services;

use Doctrine\ORM\EntityManagerInterface;

class CourseService
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function getAllCourses()
    {
        
    }
}