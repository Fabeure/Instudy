<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Annotation\Route;

class ChatController extends AbstractController
{
    #[Route('/chat/{id}', name: 'app_chat')]
    public function index($id, EntityManagerInterface $entityManager): Response
    {

        //check if I am a participant in this chat by the id, else reroute to profile


        //get my id
        $my_id = $this->getUser()->getId();

        //get participant ids for the given conversation from the route
        $participant_ids = $entityManager->getRepository(User::class)->getUserIdsForConversation($id);

        //if the conversation does not exist
         if (!($participant_ids)){
             //add error flash message
             $this->addFlash('error', 'Conversation does not exist.');

             //return to profile
             return $this->redirectToRoute('app_profile', ['username'=>($this->getUser()->getUsername())]);
    }


        //check if my id is in the participants_ids array
        if (!(($my_id == $participant_ids[0]['id'] )  or ($my_id == $participant_ids[1]['id']))){
            //add error flash message
            $this->addFlash('error', 'You cannot view this conversation.');

            //return to profile
            return $this->redirectToRoute('app_profile', ['username'=>($this->getUser()->getUsername())]);
        }

        //view conversation if all checks pass
        return $this->render('chat/index.html.twig', [
            'controller_name' => 'ChatController',
            'id' => $id
        ]);
    }

    #[Route('/chat/{id}/publish/', name: 'app_chat_publish')]
    public function publish(Request $request, HubInterface $hub): Response
    {

        //get contents of the new message
        $content = $request->request->get('value');
        $author = $request->request->get('sender');


        //create the new update that will be passed to the mercure HUB
        $update = new Update(
            'https://example.com/books/1',
            json_encode(['message' => $content,
                            'author'=> $author])
        );


        //publish update to the mercure HUB
        $hub->publish($update);

        //success message for debugging
        return new Response('published!');
    }
}
