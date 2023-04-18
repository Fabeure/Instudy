<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('User_account_settings/user_interface_settings.html.twig', ['last_username' => $lastUsername, 'error' => $error]);

    }
    #[Route(path: '/logout', name: 'app_logout')]
    public function logout()
    {
    }
}
