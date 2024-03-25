<?php

namespace App\Controller;

use App\Entity\Teacher;
use App\Form\TeacherType;
use App\Repository\TeacherRepository;
use App\Services\TeacherServices\TeacherService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route("/teachers")]
class TeacherController extends AbstractController
{
    public function __construct(
        private TeacherService $teacherService,
    )
    {
    }

    #[Route('/', name: 'app_teacher_index', methods: ["GET"])]
    public function index(): Response
    {
        $teachers = $this->teacherService->getAllTeachers();
        $this->denyAccessUnlessGranted("ROLE_ADMIN");
        return $this->render('teacher/index.html.twig', [
            'teachers' => $teachers,
        ]);
    }
    #[Route("/new", name: "app_teacher_new", methods: ["GET","POST"])]
    public function new(EntityManagerInterface $entityManager, Request $request): Response
    {
        $teacher = new Teacher();
        $form = $this->createForm(TeacherType::class, $teacher);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $teacher->setRoles(["ROLE_TEACHER"]);
            $teacher->setDtype("teacher");

            $entityManager->persist($teacher);
            $entityManager->flush();
        }
        return $this->render("teacher/new.html.twig",[
            "teacherForm" => $form->createView()
        ]);
    }
}
