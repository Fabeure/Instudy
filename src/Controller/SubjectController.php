<?php

namespace App\Controller;

use App\Entity\Matiere;
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
    #[Route('/subject', name: 'app_subject')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        //handle access control
        if(!$this->isGranted('ROLE_USER')){

            //add error flash message
            $this->addFlash('error', 'Login to access this page.');

            //return to home
            return $this->redirectToRoute('app_home');
        }


        //get all available subjects and pass them to view
        $matieres = $entityManager->getRepository(Matiere::class)->findAll();


        return $this->render('subject/index.html.twig', [
            'matieres' => $matieres
        ]);
    }
}
