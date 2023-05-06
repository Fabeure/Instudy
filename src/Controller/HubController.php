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

class HubController extends AbstractController
{
    #[Route('/hub', name: 'app_hub')]
    public function index(UserRepository $userRepository): Response
    {

        //handle access control
        if(!$this->isGranted('ROLE_USER')){

            //add error flash message
            $this->addFlash('error', 'Login to access this page.');

            //return to home
            return $this->redirectToRoute('app_home');
        }
        return $this->render('hub/index.html.twig');
    }

    #[Route('/hub/search', name: 'app_hub_search')]
    public function search(Request $request, EntityManagerInterface $entityManager, HubInterface $hub) :Response
    {
        $search = $request->request->get('query');
        $Users = $entityManager->getRepository(User::class)
            ->findByUsernameLike('%'.$search.'%');

        $data = array_map(function ($user) {
            return $user->getUsername();
        }, $Users);


        //create the new update that will be passed to the mercure HUB
        $update = new Update(
            'test',
            json_encode(['users' => $data] )
        );


        //publish update to the mercure HUB
        $hub->publish($update);
        return new Response('success');
    }

}
