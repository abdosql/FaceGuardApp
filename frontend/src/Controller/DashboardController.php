<?php

namespace App\Controller;

use App\Entity\Teacher;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DashboardController extends AbstractController
{
    #[Route(['/'], name: 'app_dashboard_index')]
    public function index(Security $security): Response
    {
        if(!$this->isGranted('ROLE_ADMIN') && !$this->isGranted('ROLE_TEACHER')){
            throw $this->createAccessDeniedException('not allowed');
        }
        $user = $security->getUser();
        $courses = null;
        if ($user instanceof Teacher) {
            $courses = $user->getCourses();
        }

        return $this->render('dashboard/index.html.twig', [
            'controller_name' => 'DashboardController',
            'courses' => $courses,
        ]);
    }
    #[Route(['/profile'], name: 'app_admin_profile', methods: ["GET"])]
    public function profile(Security $security): Response
    {
        $this->denyAccessUnlessGranted("ROLE_ADMIN");
        return $this->render('dashboard/profile.html.twig');
    }
}
