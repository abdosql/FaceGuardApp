<?php

namespace App\Controller;

use App\Entity\Teacher;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AdminController extends AbstractController
{
    #[Route(['/admin', '/'], name: 'app_admin')]
    public function index(Security $security): Response
    {
        $user = $security->getUser();
        $courses = null;
        if ($user instanceof Teacher) {
            $courses = $user->getCourses();
        }
        $this->denyAccessUnlessGranted("ROLE_USER");
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
            'courses' => $courses,
        ]);
    }
}
