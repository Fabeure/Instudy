<?php

namespace App\Controller;

use App\Utils\Utils;
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

        //handle access control
        if(!$this->isGranted('ROLE_USER')){

            //add error flash message
            $this->addFlash('error', 'Login to access this page.');

            //return to profile
            return $this->redirectToRoute('app_profile', ['username' => $this->getUser()->getUsername()]);
        }

        //get the course file path
        $Path = $this->getParameter('kernel.project_dir') . '/public/assets/files/course_files/' .$filePath;

        //convert the course from pdf to raw text using spatie
        $text = Pdf::getText($Path);

        //pass the raw course text to view (important for passing it to chatGPT via ajax)
        return $this->render('ChatGPT/gpt.html.twig', [
            'text' => $text
        ]);
    }


    //handle incoming prompt requests for real time responses
    #[Route('/gptChat', name: 'send_chat', methods:"POST")]
    public function chat(Request $request, HubInterface $hub): Response
    {
        //get the question contents
        $question=$request->request->get('text');


        //set the API key
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

        //get the top#1 result content
        $response=$result->choices[0]->message->content;

        //create a new update containing chatGPT's response in raw text format
        Utils::Realtime("testGPT",['response' => $response], $hub );
        return new Response('success');
    }




}