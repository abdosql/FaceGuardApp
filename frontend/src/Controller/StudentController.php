<?php

namespace App\Controller;

use App\Entity\Student;
use App\Form\StudentType;
use App\Services\GroupService;
use App\Services\notificationServices\EmailNotificationService;
use App\Services\userServices\StudentService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/students')]
class StudentController extends AbstractController
{
    public function __construct(
        private StudentService $studentService,
        private GroupService $groupService,
    )
    {
    }

    #[Route('/', name: 'app_student_index', methods: ['GET'])]
    public function index(): Response
    {
        $this->denyAccessUnlessGranted("ROLE_ADMIN");
        return $this->render('student/index.html.twig', [
            'students' => $this->studentService->getAllStudents(),
            'studentsWithoutGroupExists' => $this->studentService->studentsWithoutGroupExist(),
            'totalStudents' => $this->studentService->getStudentsCount(),
            'totalGroups' => $this->groupService->countGroups(),
            "countGenders" => $this->studentService->countStudentsPercentageByGender()
        ]);
    }

    /**
     * @throws TransportExceptionInterface
     */
    #[Route('/new', name: 'app_student_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EmailNotificationService $emailNotificationService): Response
    {
        $this->denyAccessUnlessGranted("ROLE_ADMIN");
        $student = new Student();
        $form = $this->createForm(StudentType::class, $student);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $data = $this->studentService->saveStudent($student);
            $this->addFlash("success", $data);
            $emailNotificationService->sendMessage($student->getEmail(),"Here are you're credentials.", $data);
            return $this->redirectToRoute("app_student_index",[], Response::HTTP_SEE_OTHER);
        }
        return $this->render("student/new.html.twig",[
            "studentForm" => $form->createView()
        ]);
    }

    #[Route('/{id}', name: 'app_student_show', methods: ['GET'])]
    public function show(Student $student): Response
    {
        return $this->render('student/show.html.twig', [
            'user' => $student,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_student_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Student $student, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(StudentType::class, $student);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_student_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('student/edit.html.twig', [
            'student' => $student,
            'form' => $form,
        ]);
    }

    #[Route("{id}/delete/", name: "app_student_delete", methods: ["POST"])]
    public function delete(Request $request,Student $student): Response
    {
        $this->denyAccessUnlessGranted("ROLE_ADMIN");
        if ($this->isCsrfTokenValid('delete'.$student->getId(), $request->request->get('_token'))) {
            $this->studentService->deleteStudent($student);
        }
        return $this->redirectToRoute("app_teacher_index");
    }

    #[Route('/groups/generate', name: 'app_students_generate_groups')]
    public function generateGroups(): Response
    {
        $this->denyAccessUnlessGranted("ROLE_ADMIN");
        $this->studentService->AssignStudentsToCovenantGroupRandomly($this->groupService, 36);
        return $this->redirectToRoute("app_student_index");
    }
}
