<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{

    #[Route('/profile', name: 'app_profile')]
    public function index(): Response
    {
        //handle access control
        $this->denyAccessUnlessGranted('ROLE_USER', null, 'You must be a user to access this page ///// add reroute ');
        return $this->render('profile/index.html.twig', [
            'controller_name' => 'ProfileController',
        ]);
    }
}
