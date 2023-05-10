<?php

namespace App\Controller;

use App\Entity\Conversation;
use App\Entity\Message;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Annotation\Route;

class ChatController extends AbstractController
{
    /**
     * @throws NonUniqueResultException
     */
    #[Route('/chat/{id}', name: 'app_chat')]
    public function index($id, EntityManagerInterface $entityManager): Response
    {

        //handle access control
        if(!$this->isGranted('ROLE_USER')){

            //add error flash message
            $this->addFlash('error', 'You cannot view this page.');

            //return to profile
            return $this->redirectToRoute('app_profile', ['username' => $this->getUser()->getUsername()]);
        }

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


        //get the id that ISN'T mine
        $other_id = ($my_id == $participant_ids[0]['id']) ? $participant_ids[1]['id'] : $participant_ids[0]['id'];


        //fetch the other user from the database
        $other_user = $entityManager->getRepository(User::class)->find($other_id);


        //get the conversation
        $conversation = $entityManager->getRepository(Conversation::class)->find($id);


        //fetch past messages
        $messages = $entityManager->getRepository(Message::class)->findBy(['conversation'=>$conversation], limit:20, orderBy: ['id' => 'DESC']);


        //put past messages in an array to pass to the front end; //NEED TO MAKE IT MAX 10 OR 15 MESSAGES
        $history=array_map(function($message) {
            return [
                'content' => $message->getContent(),
                'author' => $message->getUser()->getUsername(),
            ];
        }, array_reverse($messages));


        //pass others username's id, conversation id and past messages to view
        return $this->render('chat/index.html.twig', [
            'messages'=> $history,
            'other_user' => $other_user,
            'id' => $id
        ]);
    }

    #[Route('/sendChat', name: 'app_sendChat')]
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
