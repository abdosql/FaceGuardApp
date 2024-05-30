<?php

namespace App\Controller;

use App\Services\AttendanceService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AttendanceController extends AbstractController
{
    public function __construct(private AttendanceService $attendanceService)
    {
    }

    #[Route('/attendance/{rfid}/attending', name: 'app_attendance')]
    public function isAttending(string $rfid): JsonResponse
    {

        $attendance = $this->attendanceService->studentIsPresent($rfid);
        return $this->json($attendance);
    }
}
