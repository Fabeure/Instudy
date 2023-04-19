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

        $users = [
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

        $demandes = [
            $d1 = [
                'id' => '1',
                'username' => 'User 1',
                'date' => '2021-05-01',
                'title' => 'Demande 1',
            ],
            $d2 = [
                'id' => '2',
                'username' => 'User 2',
                'date' => '2021-05-02',
                'title' => 'Demande 2',
            ],
            $d3 = [
                'id' => '3',
                'username' => 'User 3',
                'date' => '2021-05-03',
                'title' => 'Demande 3',
            ],
            $d4 = [
                'id' => '4',
                'username' => 'User 4',
                'date' => '2021-05-04',
                'title' => 'Demande 4',
            ],
        ];


        return $this->render('admin_pannel/index.html.twig', [
            'controller_name' => 'AdminPannelController',
            'data' => [
                'users' => $users,
                'demandes' => $demandes
            ],
        ]);
    }
}
