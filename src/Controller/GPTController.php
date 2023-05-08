<?php

namespace App\Controller;

use OpenAI;
use Spatie\PdfToText\Pdf;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GPTController extends AbstractController
{
    #[Route('/gpt/{filePath}', name: 'app_gpt')]
    public function index( $filePath, ? string $question, ? string $response): Response
    {
        $Path = $this->getParameter('kernel.project_dir') . '/public/assets/files/course_files/' .$filePath;
        $text = Pdf::getText($Path);
        return $this->render('ChatGPT/gpt.html.twig', [
            'text' => $text
        ]);
    }



    #[Route('/gptChat', name: 'send_chat', methods:"POST")]
    public function chat(Request $request, HubInterface $hub): Response
    {
        $question=$request->request->get('text');

        //set API key

        $myApiKey = $_ENV['OPENAI_KEY'];

        //setup client connection
        $client = OpenAI::client($myApiKey);

        //get result back
        $result = $client->chat()->create([
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                ['role' => 'assistant', 'content' => $question],
            ],
        ]);

        $response=$result->choices[0]->message->content;

        $update = new Update(
            "testGPT",
            json_encode(['response' => $response] )
        );


        //publish update to the mercure HUB
        $hub->publish($update);
        return new Response('success');
    }




}