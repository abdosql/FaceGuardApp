<?php

namespace App\Controller;

use App\Entity\Teacher;
use App\Form\TeacherType;
use App\Services\notificationServices\EmailNotificationService;
use App\Services\userServices\TeacherService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
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

    /**
     * @throws TransportExceptionInterface
     */
    #[Route("/new", name: "app_teacher_new", methods: ["GET","POST"])]
    public function new(Request $request, EmailNotificationService $emailNotificationService): Response
    {
        $teacher = new Teacher();
        $form = $this->createForm(TeacherType::class, $teacher);
        $form->handleRequest($request);
        $password = $this->teacherService->generateRandomPassword($teacher);
        if ($form->isSubmitted() && $form->isValid()){

            $teacher->setUsername(
                $this->teacherService
                    ->generateUniqueUsername(
                        $teacher->getFirstName(),
                        $teacher->getLastName())
            );
            $teacher->setPassword($password["hashedPassword"]);
            $teacher->setRoles(["ROLE_TEACHER"]);
            $teacher->setDtype("teacher");
            $teacher->setProfileImage("something");
            $this->teacherService->saveTeacher($teacher);
            $data = [
                "username" => $teacher->getUsername(),
                "password" => $password["password"]
            ];
            $this->addFlash("success", $data);
            $emailNotificationService->sendMessage($teacher->getEmail(),"Here are you're credentials.", $data);
            return $this->redirectToRoute("app_teacher_index");
        }
        return $this->render("teacher/new.html.twig",[
            "teacherForm" => $form->createView()
        ]);
    }
    #[Route("{id}/delete/", name: "app_teacher_delete", methods: ["POST"])]
    public function delete(Teacher $teacher): Response
    {
        $this->teacherService->deleteTeacher($teacher);
        return $this->redirectToRoute("app_teacher_index");
    }
}
