<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils, Security $security): Response
    {
        if ($security->isGranted("IS_AUTHENTICATED_FULLY")){
            return $this->redirectToRoute("app_admin");
        }
        return $this->render('security/login.html.twig', [
            "error" => $authenticationUtils->getLastAuthenticationError(),
            "last_username" => $authenticationUtils->getLastUsername(),
        ]);
    }
    #[Route("/logout", name: 'app_logout')]
    public function logout(){
        throw new \Exception("This Route should never be reached");
    }
}
