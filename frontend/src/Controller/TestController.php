<?php

namespace App\Controller;

use App\Services\AutoGeneratorsServices\TimeScheduleGenerator;
use App\Services\ScheduleApiService;
use App\Services\ScheduleService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class TestController extends AbstractController
{
    public function __construct(private TimeScheduleGenerator $scheduleService,private ScheduleApiService $scheduleApiService)
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

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws \Exception
     */
    #[Route('/genTest', name: 'apptest')]

    public function generate(): JsonResponse
    {
        // Call the generateSchedules method from the service
        $schedule = $this->scheduleService->generateSchedules();

        // Check if the schedule is empty (indicating an error or no data)
        if (empty($schedule)) {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'Could not generate schedule, check your data or API.'
            ], 500);
        }

        // Return the schedule data in a JSON response
        return new JsonResponse([
            'status' => 'success',
            'data' => $schedule
        ]);
    }
}
