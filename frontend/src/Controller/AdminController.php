<?php

namespace App\Controller;

use App\Entity\Teacher;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AdminController extends AbstractController
{
    #[Route(['/'], name: 'app_admin')]
    public function index(Security $security): Response
    {
        $user = $security->getUser();
        $courses = null;
        if ($user instanceof Teacher) {
            $courses = $user->getCourses();
        }
        $this->denyAccessUnlessGranted("ROLE_ADMIN");
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
            'courses' => $courses,
        ]);
    }
    #[Route(['/profile'], name: 'app_admin_profile', methods: ["GET"])]
    public function profile(Security $security): Response
    {
        $this->denyAccessUnlessGranted("ROLE_ADMIN");
        return $this->render('admin/profile.html.twig');
    }
}
