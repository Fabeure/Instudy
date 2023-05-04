<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HubController extends AbstractController
{
    #[Route('/hub', name: 'app_hub')]
    public function index(): Response
    {
        //handle access control
        $this->denyAccessUnlessGranted('ROLE_USER', null, 'You must be a user to access this page ///// add reroute ');
        return $this->render('hub/index.html.twig', [
            'controller_name' => 'HubController',
        ]);
    }
    /**
     * @Route("/hub", name="search_profile", methods={"POST"})
     */
    public function  searchProfile(Request $request){

        $Users =   $this->UserRepository->findAll();
        $Names = [];
        for ($i=0; $i<count($Users); $i++){
            $Names[$i] =$Users[$i]->getName() ;
        }
        return  $this->JSON($Names) ;
    }
}
