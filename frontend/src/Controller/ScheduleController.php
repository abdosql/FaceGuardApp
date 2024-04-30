<?php

namespace App\Controller;

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
        private GroupService $groupService,
    )
    {
    }

    #[Route('/{slug?}', name: 'app_schedule_index', methods: ['GET'])]
    public function index($slug): Response
    {
        return $this->render('schedule/index.html.twig', [
            'groups' => $this->groupService->explodedGroupName(),
            'slug' => $slug,
        ]);
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

}
