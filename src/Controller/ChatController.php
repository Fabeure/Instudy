<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ChatController extends AbstractController
{
    #[Route('/chat/{username}', name: 'app_chat')]
    public function index($username): Response
    {
        return $this->render('chat/index.html.twig', [
            'controller_name' => 'ChatController',
            'username' => $username
        ]);
    }
}
