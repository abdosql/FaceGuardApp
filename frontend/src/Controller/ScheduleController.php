<?php

namespace App\Controller;

use App\Services\AcademicYearService;
use App\Services\GroupService;
use App\Services\ScheduleService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/schedules')]
class ScheduleController extends AbstractController
{
    public function __construct(
        private ScheduleService $scheduleService,
        private AcademicYearService $academicYearService,
        private GroupService $groupService,
    )
    {
    }
    #[Route('/', name: 'app_schedule_index', methods: ['GET'])]
    public function index(): Response{
//        dd($this->academicYearService->getAllAcademicYears());
        return $this->render('schedule/index.html.twig', [
            'years' => $this->academicYearService->getAllAcademicYears(),
            'groupService' => $this->groupService,
        ]);
    }
    #[Route('/group/{id}', name: 'app_group_schedule', methods: ['GET'])]
    public function getSchedule(int $id): Response{
        return $this->render('schedule/schedule.html.twig', [
            'years' => $this->academicYearService->getAllAcademicYears(),
            'schedules' => $this->scheduleService->fullCalenderDataStructure(1,$id),
        ]);
    }

    #[Route("/api/schedules", name : "get_schedules")]
    public function getSchedules(Request $request): JsonResponse
    {
        $semesterId = $request->query->get('semesterId');
        $groupId = $request->query->get('groupId');

        $schedules = $this->scheduleService->fullCalenderDataStructure($semesterId, $groupId);
        return new JsonResponse($schedules);
    }
    #[Route('/delete-event', name: 'delete_event', methods: ['POST'])]
    public function deleteEvent(Request $request): JsonResponse
    {
        // Get the event ID from the request body
        $data = json_decode($request->getContent(), true);
        $eventId = $data['id'] ?? null;

        // Implement your logic to delete the event based on the event ID
        // For example:
        // $entityManager = $this->getDoctrine()->getManager();
        // $event = $entityManager->getRepository(Event::class)->find($eventId);
        // if ($event) {
        //     $entityManager->remove($event);
        //     $entityManager->flush();
        // }

        // Return a JSON response indicating success
        return new JsonResponse(['message' => 'Event deleted successfully', 'eventId' => $eventId], Response::HTTP_OK);
    }
    #[Route('/teacher', name: 'app_teacher_schedule', methods: ['GET'])]
    public function showSchedule(): Response
    {
//        dd($this->scheduleService->findTeacherSessions($teacher->getId()));
        return $this->render('schedule/schedule.html.twig', [
            'schedules' => $this->scheduleService->fullCalenderDataStructureTeachers($this->getUser()->getId()),

        ]);
    }

}
