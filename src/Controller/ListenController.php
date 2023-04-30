<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ListenController extends AbstractController
{
    #[Route('/listen', name: 'app_listen')]
    public function index(): Response
    {
        return $this->render('listen/index.html.twig', [
            'controller_name' => 'ListenController',
        ]);
    }
}
