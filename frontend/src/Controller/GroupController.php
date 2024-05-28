<?php

namespace App\Controller;

use App\Entity\AcademicYear;
use App\Entity\Branch;
use App\Services\GroupService;
use App\Services\userServices\StudentService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class GroupController extends AbstractController
{
    public function __construct(private GroupService $groupService, private StudentService $studentService)
    {
    }

    #[Route('/schedules/groups/{academicYear}/{branch}', name: 'app_getGroups')]
    public function index(AcademicYear $academicYear, Branch $branch): Response
    {
        $this->denyAccessUnlessGranted("ROLE_ADMIN");
        return $this->render('group/index.html.twig', [
                'groups' => $this->groupService->getGroupsByYearAndBranch($academicYear, $branch),
        ]);
    }
    #[Route('/groups/', name: 'app_getg')]
    public function getGroups(): Response
    {
        return $this->render('student/students.html.twig', [
        ]);
    }

}
