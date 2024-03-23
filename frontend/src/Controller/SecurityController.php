<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils, EntityManagerInterface $entityManager): Response
    {

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
