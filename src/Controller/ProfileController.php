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
        $this->denyAccessUnlessGranted('ROLE_USER', null, 'You must be a user to access this page ');

        //if 'profile/sittings' it will be forwarded to ProfileSittingsController
        if( strcmp($username ,'settings') == 0 ){
            return $this->forward(controller: 'App\Controller\ProfileSettingsController::index');
        } else {
            $user = $userRepository->findOneBy(['username' => $username]);

            //if there's no user with this username
            if (!$user) {
                $this->addFlash('error', $username . ' not found !');
                return $this->render('home/index.html.twig');
            }

            else {
                return $this->render('profile/index.html.twig', [
                    'user' => $user,
                ]);
            }
        }
    }
}