<?php

namespace App\Controller;

use App\Repository\TeacherRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
class TeacherController extends AbstractController
{
    #[Route('/teachers', name: 'app_teacher_index', methods: "GET")]
    public function index(TeacherRepository $teacherRepository): Response
    {
        $teachers = $teacherRepository->findAll();
        $this->denyAccessUnlessGranted("ROLE_ADMIN");
        return $this->render('teacher/index.html.twig', [
            'teachers' => $teachers,
        ]);
    }
}
