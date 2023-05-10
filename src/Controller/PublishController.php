<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Annotation\Route;

class PublishController extends AbstractController
{
    #[Route('/publish', name: 'app_publish')]
    public function publish(Request $request, HubInterface $hub): Response
    {
        //handle access control
        if(!$this->isGranted('ROLE_USER')) {

            //add error message for unauthorised access
            $this->addFlash('error', 'Login to access this page.');

            //return to home
            return $this->redirectToRoute('app_home');
        }

        //get contents of the publish request
        $content = $request->request->get('value');
        $author = $request->request->get('sender');
        $convo_id = $request->request->get('convo_id');


        //create the appropriate topic to listen to
        $topic = ''.$convo_id;


        //create the new update that will be passed to the mercure HUB
        $update = new Update(
            $topic,
            json_encode(['message' => $content,
                'author'=> $author])
        );


        //publish update to the mercure HUB
        $hub->publish($update);

        //success message for debugging
        return new Response('published!');
    }
}
