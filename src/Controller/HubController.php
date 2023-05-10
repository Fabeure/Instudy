<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Utils\Utils;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class HubController extends AbstractController
{
    #[Route('/hub', name: 'app_hub')]
    public function index(UserRepository $userRepository): Response
    {

        //handle access control
        if (!$this->isGranted('ROLE_USER')) {

            //add error flash message
            $this->addFlash('error', 'Login to access this page.');

            //return to home
            return $this->redirectToRoute('app_home');
        }
        return $this->render('hub/index.html.twig', [
            'isTeacher' => $this->isGranted('ROLE_TEACHER')
        ]);
    }


    //route to handle real time search requests
    #[Route('/hub/search', name: 'app_hub_search')]
    public function search(Request $request, EntityManagerInterface $entityManager, HubInterface $hub): Response
    {

        //get the search parametre from the ajax request
        $search = $request->request->get('query');

        //get all users matching the pattern
        $Users = $entityManager->getRepository(User::class)->findByUsernameLike('%' . $search . '%');


        //put all users in array
        $data = array_map(function ($user) {
            return $user->getUsername();
        }, $Users);


       Utils::Realtime($this->getUser()->getUsername(), ['users' => $data], $hub);
       return new Response('success');
    }
}
