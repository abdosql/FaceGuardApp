<?php

namespace App\Services;

use Doctrine\ORM\EntityManagerInterface;

class ScheduleService
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

}