<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class SubjectController extends AbstractController
{
    #[Route('/Subject', name: 'app_subj')]
    public function index(): Response
    {

        return $this->render('course/list.html.twig', [
            'controller_name' => 'SubjectController',
        ]);
    }
}
