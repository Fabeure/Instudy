<?php

namespace App\Controller;

use phpDocumentor\Reflection\PseudoTypes\True_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminPannelController extends AbstractController
{
    #[Route('/adminPannel', name: 'app_admin_pannel')]
    public function index(): Response
    {

        $data = [
            $user1 = [
                'profilePic' => 'assets/images/defaultProfilePic.png',
                'username' => 'User 1',
                'isActive' => True,
            ],
            $user2 = [
                'profilePic' => 'assets/images/defaultProfilePic.png',
                'username' => 'User 2',
                'isActive' => True,
            ],
            $user3 = [
                'profilePic' => 'assets/images/defaultProfilePic.png',
                'username' => 'User 3',
                'isActive' => False,
            ],
            $user4 = [
                'profilePic' => 'assets/images/defaultProfilePic.png',
                'username' => 'User 4',
                'isActive' => True,
            ],
            $user5 = [
                'profilePic' => 'assets/images/defaultProfilePic.png',
                'username' => 'User 5',
                'isActive' => True,
            ],
            $user6 = [
                'profilePic' => 'assets/images/defaultProfilePic.png',
                'username' => 'User 6',
                'isActive' => True,
            ],
        ];


        return $this->render('admin_pannel/index.html.twig', [
            'controller_name' => 'AdminPannelController',
            'data' => $data,
        ]);
    }
}
