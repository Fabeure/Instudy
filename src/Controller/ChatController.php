<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Annotation\Route;

class ChatController extends AbstractController
{
    #[Route('/chat/{id}', name: 'app_chat')]
    public function index($id): Response
    {
        return $this->render('chat/index.html.twig', [
            'controller_name' => 'ChatController',
            'id' => $id
        ]);
    }

    #[Route('/chat/{id}/publish/', name: 'app_chat_publish')]
    public function publish(Request $request, HubInterface $hub): Response
    {
        $content = $request->request->get('value');
        $author = $request->request->get('sender');
        $update = new Update(
            'https://example.com/books/1',
            json_encode(['message' => $content,
                            'author'=> $author])
        );

        $hub->publish($update);

        return new Response('published!');
    }
}
