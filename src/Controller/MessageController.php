<?php

namespace App\Controller;

use App\Entity\Conversation;
use App\Entity\Message;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MessageController extends AbstractController
{
    /**
     * @throws OptimisticLockException
     * @throws \Doctrine\DBAL\Exception
     * @throws ORMException
     */
    #[Route('/newMessage', name: 'app_newMessage')]
    public function newMessage(Request $request, EntityManagerInterface $entityManager)
    {
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


        //save message
        $entityManager->getRepository(Message::class)->save($message, true);
        $entityManager->getRepository(Conversation::class)->save($conversation, true);

        //success message for debugging
        return new Response('published!');
    }
}