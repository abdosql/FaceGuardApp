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
        $this->denyAccessUnlessGranted("ROLE_ADMIN");
        return $this->render('teacher/index.html.twig', [
            'teachers' => $this->teacherService->getAllTeachers(),
        ]);
    }

    /**
     * @throws TransportExceptionInterface
     */
    #[Route("/new", name: "app_teacher_new", methods: ["GET","POST"])]
    public function new(Request $request, EmailNotificationService $emailNotificationService): Response
    {
        $this->denyAccessUnlessGranted("ROLE_ADMIN");
        $teacher = new Teacher();
        $form = $this->createForm(TeacherType::class, $teacher);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $data = $this->teacherService->saveTeacher($teacher, $form);
            $this->addFlash("success", $data);
            $emailNotificationService->sendMessage($teacher->getEmail(),"Here are you're credentials.", $data);
            return $this->redirectToRoute("app_teacher_index",[], Response::HTTP_SEE_OTHER);
        }
        return $this->render("teacher/new.html.twig",[
            "teacherForm" => $form->createView()
        ]);
    }
    #[Route("{id}/delete/", name: "app_teacher_delete", methods: ["POST"])]
    public function delete(Request $request,Teacher $teacher): Response
    {
        $this->denyAccessUnlessGranted("ROLE_ADMIN");
        if ($this->isCsrfTokenValid('delete'.$teacher->getId(), $request->request->get('_token'))) {
            $this->teacherService->deleteTeacher($teacher);
        }
        return $this->redirectToRoute("app_teacher_index");
    }
    #[Route('/{id}', name: 'app_teacher_show', methods: ['GET'])]
    public function show(Teacher $teacher): Response
    {
        return $this->render('teacher/show.html.twig', [
            'user' => $teacher,
            'studentsCount' => $this->teacherService->countStudentsByTeacher($teacher)
        ]);
    }
}
