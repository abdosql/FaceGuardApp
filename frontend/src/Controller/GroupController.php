<?php

namespace App\Controller;

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

    #[Route('/group', name: 'app_group')]
    public function index(): Response
    {
        return $this->render('group/index.html.twig', [
            'controller_name' => 'GroupController',
        ]);
    }
}
