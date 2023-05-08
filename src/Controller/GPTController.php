<?php

namespace App\Controller;

use OpenAI;
use Spatie\PdfToText\Pdf;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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