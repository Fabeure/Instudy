<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
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

        return $this->render('home/index.html.twig', ['last_username' => $lastUsername, 'error' => $error]);

    }
    
    #[Route(path: '/logout', name: 'app_logout')]
    public function logout()
    {
    }

    #[Route(path: '/generate_cheatsheet', name: 'generate_cheatsheet')]
    public function generateCheatsheet(Request $request)
    {
        $text = $request->request->get('text');
        // generate cheatsheet based on $text
        $cheatsheet = '...'; // your cheatsheet as a string
        $response = new Response($cheatsheet);
        $response->headers->set('Content-Type', 'text/plain');
        return $response;
    }
}
