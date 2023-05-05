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
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class HubController extends AbstractController
{
    #[Route('/hub', name: 'app_hub')]
    public function index(UserRepository $userRepository): Response
    {
        //handle access control
        $this->denyAccessUnlessGranted('ROLE_USER', null, 'You must be a user to access this page ');

        $user = $userRepository->findByUsernameLike('%nao%');

        $usersName = array_map(fn ($user) => $user->getUsername(), $user);

        return $this->render('hub/index.html.twig', [
            'users' => $usersName
        ]);
    }

    #[Route('/hub/search', name: 'app_hub_search')]
    public function search(Request $request, EntityManagerInterface $entityManager, HubInterface $hub) :Response
    {
        $query = $request->request->get('query');
        $Users = $entityManager->getRepository(User::class)
            ->findByUsernameLike('%'.$query.'%');

        $data = [];
        $i= 0;

        foreach ($Users as $user) {
            $data[$i] =
                $user->getUsername();
            $i += 1;
        }
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
