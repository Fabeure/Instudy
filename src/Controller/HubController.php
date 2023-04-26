<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HubController extends AbstractController
{
    #[Route('/hub', name: 'app_hub')]
    public function index(): Response
    {
        //handle access control
        $this->denyAccessUnlessGranted('ROLE_USER', null, 'You must be a user to access this page ///// add reroute ');
        return $this->render('hub/index.html.twig', [
            'controller_name' => 'HubController',
        ]);
    }
}