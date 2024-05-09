<?php

namespace App\Controller;

use App\Services\ScheduleService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TestController extends AbstractController
{
    public function __construct(private ScheduleService $scheduleService)
    {
    }

    #[Route('/schedules_test', name: 'app_test')]
    public function index(): Response
    {
        $schedules = $this->scheduleService->getSchedules();
        dd($schedules[0]->getSessions()[0]->getCourse()->getCourseName());
        return $this->render('test/index.html.twig', [
            'controller_name' => 'TestController',
            'schedules' => $schedules
        ]);
    }
    #[Route('/genTest', name: 'apptest')]

    public function generateSchedules(): Response
    {
        $this->scheduleService->generateSchedules();
        return New Response("Done");
    }
}
