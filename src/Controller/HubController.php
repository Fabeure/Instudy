<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HubController extends AbstractController
{
    #[Route('/hub', name: 'app_hub')]
    public function index(UserRepository $userRepository): Response
    {
        //handle access control
        $this->denyAccessUnlessGranted('ROLE_USER', null, 'You must be a user to access this page ');

        $user = $userRepository->findByUsernameLike('%s%');

        $usersName = array_map(fn ($user) => $user->getUsername(), $user);

        return $this->render('hub/index.html.twig', [
            'users' => $usersName
        ]);
    }

}
