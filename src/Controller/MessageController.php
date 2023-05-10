<?php

namespace App\Controller;

use App\Entity\Conversation;
use App\Entity\Message;
use App\Entity\Notification;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Routing\Annotation\Route;

class MessageController extends AbstractController
{
    /**
     * @throws OptimisticLockException
     * @throws \Doctrine\DBAL\Exception
     * @throws ORMException
     */
    #[Route('/newMessage', name: 'app_newMessage')]
    public function newMessage(Request $request, EntityManagerInterface $entityManager, HubInterface $hub)
    {
        //handle access control
        if(!$this->isGranted('ROLE_USER')){

            //add error flash message
            $this->addFlash('error', 'Login to access this page.');

            //return to profile
            return $this->redirectToRoute('app_profile', ['username' => $this->getUser()->getUsername()]);
        }

        //get my id
        $my_id = $this->getUser()->getId();


        //get contents of request
        $content = $request->request->get('value', null);
        $author = $request->request->get('sender');
        $conversation_id = $request->request->get('conversation_id');

        //get convo from database
        $conversation = $entityManager->getRepository(Conversation::class)->find($conversation_id);

        //create new message
        $message = new Message();
        $message->setContent($content);
        $message->setConversation($conversation);
        $message->setUser($entityManager->getRepository(User::class)->findOneBy(['username' => $author]));
        $message->setMine(true);

        //add message as last sent to conversation
        $conversation->addMessage($message);
        $conversation->setLastMessage($message);


        //fetch second user from conversation to send the notification to
        $participant_ids = $entityManager->getRepository(User::class)->getUserIdsForConversation($conversation_id);
        $other_id = ($my_id == $participant_ids[0]['id']) ? $participant_ids[1]['id'] : $participant_ids[0]['id'];
        $other_user = $entityManager->getRepository(User::class)->findOneBy(['id'=>$other_id]);


        //create new notification
        $notif = new Notification();
        //create the url attached to the notification
        $url = "/chat/".$conversation_id;
        //add notification
        $entityManager->getRepository(Notification::class)->addNotification($url, $notif, $this->getUser(), $other_user, "New Message", $hub);


        //persist everything to database
        $entityManager->getRepository(Message::class)->save($message, true);
        $entityManager->getRepository(Conversation::class)->save($conversation, true);
        $entityManager->getRepository(Notification::class)->save($notif, true);


        //success message for debugging
        return new Response('published!');
    }
}