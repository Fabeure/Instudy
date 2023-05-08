<?php

namespace App\Controller;

use OpenAI;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GPTController extends AbstractController
{
    #[Route('/gpt', name: 'app_gpt')]
    public function index( ? string $question, ? string $response): Response
    {
        return $this->render('ChatGPT/gpt.html.twig', [
            'question' => $question,
            'response' => $response
        ]);
    }



    #[Route('/gpt/chat', name: 'send_chat', methods:"POST")]
    public function chat(Request $request): Response
    {
        $question=$request->request->get('text');

        //Implémentation du chat gpt

        $myApiKey = $_ENV['OPENAI_KEY'];


        $client = OpenAI::client($myApiKey);

        $result = $client->completions()->create([
            'model' => 'text-davinci-003',
            'prompt' => $question,
            'max_tokens'=>2048
        ]);

        $response=$result->choices[0]->text;


        return $this->forward('App\Controller\GPTController::index', [

            'question' => $question,
            'response' => $response
        ]);
    }




}