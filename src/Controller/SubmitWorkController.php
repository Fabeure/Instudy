<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SubmitWorkController extends AbstractController
{
    #[Route('/submit/work', name: 'app_submit_work')]
    public function index(): Response
    {
        return $this->render('submit_work/index.html.twig', [
            'controller_name' => 'SubmitWorkController',
        ]);
    }
}
