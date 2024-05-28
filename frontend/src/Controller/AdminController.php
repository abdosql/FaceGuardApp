<?php

namespace App\Controller;

use App\Entity\Admin;
use App\Form\AdminType;
use App\Repository\AdminRepository;
use App\Services\notificationServices\EmailNotificationService;
use App\Services\userServices\AdminService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin')]
class AdminController extends AbstractController
{
    public function __construct(private AdminService $adminService)
    {
    }

    #[Route('/', name: 'app_admin_index', methods: ['GET'])]
    public function index(): Response
    {
        $this->denyAccessUnlessGranted(["ROLE_ADMIN", "ROLE_TEACHER"]);
        return $this->render('admin/index.html.twig', [
            'admins' => $this->adminService->getAllAdministrators(),
        ]);
    }

    /**
     * @throws TransportExceptionInterface
     */
    #[Route('/new', name: 'app_admin_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EmailNotificationService  $emailNotificationService): Response
    {
        $this->denyAccessUnlessGranted("ROLE_ADMIN");
        $admin = new Admin();
        $form = $this->createForm(AdminType::class, $admin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $this->adminService->saveAdministrator($admin);
            $this->addFlash("success", $data);
            $emailNotificationService->sendMessage($admin->getEmail(),"Here are you're credentials.", $data);
            return $this->redirectToRoute('app_admin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/new.html.twig', [
            'admin' => $admin,
            "adminForm" => $form->createView()
        ]);
    }

    #[Route('/{id}', name: 'app_admin_show', methods: ['GET'])]
    public function show(Admin $admin): Response
    {
        return $this->render('admin/show.html.twig', [
            'user' => $admin,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Admin $admin, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AdminType::class, $admin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/edit.html.twig', [
            'admin' => $admin,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_delete', methods: ['POST'])]
    public function delete(Request $request, Admin $admin, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted("ROLE_ADMIN");
        if ($this->isCsrfTokenValid('delete'.$admin->getId(), $request->request->get('_token'))) {
            $entityManager->remove($admin);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_index', [], Response::HTTP_SEE_OTHER);
    }
}
