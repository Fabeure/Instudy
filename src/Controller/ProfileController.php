<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UserRepository;

class ProfileController extends AbstractController
{

    #[Route('/profile/{username}', name: 'app_profile')]
    public function index($username, UserRepository $userRepository): Response
    {
        //handle access control
        if(!$this->isGranted('ROLE_USER')){

            //add error flash message
            $this->addFlash('error', 'Login to access this page.');

            //return to home
            return $this->redirectToRoute('app_home');
        }

        $user = $userRepository->findOneBy(['username' => $username]);

        //if there's no user with this username
        if (!$user) {
            $this->addFlash('error', $username . ' user does not exist !');
            return $this->render('home/index.html.twig');
        } else {
            return $this->render('profile/index.html.twig', [
                'user' => $user,
                'username' => $username
            ]);
        }
    }

}