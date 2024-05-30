<?php

namespace App\Controller;

use App\Services\AttendanceService;
use App\Services\SessionService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SessionController extends AbstractController
{
    public function __construct(private SessionService $sessionService, private AttendanceService $attendanceService)
    {
    }

    #[Route('/session/current', name: 'app_session_current')]
    public function index(): Response
    {
        $session = $this->sessionService->getCurrentSessionForTeacher($this->getUser()->getId());
        if ($session){
            $group = $session->getTimeSchedule()->getGroup();
            $students = $group->getStudents();
        //        dd($this->sessionService->getCurrentSessionForTeacher($this->getUser()->getId()));
            return $this->render('session/index.html.twig', [
                'session' => $session,
                'group' => $group,
                'students' => $students,
                'attendance' => $this->attendanceService,
            ]);
        }
        return $this->render('session/index.html.twig', [
            'session' => $session,
        ]);
    }
}
