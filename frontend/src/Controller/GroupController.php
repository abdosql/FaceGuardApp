<?php

namespace App\Controller;

use App\Services\GroupServices\GroupService;
use App\Services\userServices\StudentService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class GroupController extends AbstractController
{
    public function __construct(private GroupService $groupService, private StudentService $studentService)
    {
    }

    #[Route('/group', name: 'app_group')]
    public function index(): Response
    {
        return $this->render('group/index.html.twig', [
            'controller_name' => 'GroupController',
        ]);
    }
    #[Route('/students/groups/generate', name: 'app_generate_groups')]
    public function generateGroups(): Response
    {
        $this->denyAccessUnlessGranted("ROLE_ADMIN");
        $this->groupService->generateGroups($this->studentService->getStudentsByAcademicYear(), 36);
        return $this->redirectToRoute("app_student_index");
    }
}
