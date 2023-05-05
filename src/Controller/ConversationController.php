<?php

namespace App\Controller;

use App\Entity\Conversation;
use App\Entity\Participant;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ConversationController extends AbstractController
{
    /**
     * @throws Exception
     */
    #[Route('/newConversations/{username}', name: 'app_newConversations')]
    public function index($username, EntityManagerInterface $entityManager): Response
    {

        //handle access control
        if(!$this->isGranted('ROLE_USER')){

            //add error flash message
            $this->addFlash('error', 'You cannot view this page.');

            //return to home
            return $this->redirectToRoute('app_profile', ['username' => $this->getUser()->getUsername()]);
        }

        // get the user you are trying to start a conversation with
        $otherUser = $entityManager->getRepository(User::class)->findOneBy(['username'=>$username]);


        //check if the other user you are starting a conversation with exists
        if (is_null($otherUser)) {
            $this->addFlash('error', 'User does not exist.');
            return $this->redirectToRoute('app_profile', ['username'=>($this->getUser()->getUsername())]);
        }

        // cannot create a conversation with myself
        if ($otherUser->getId() === $this->getUser()->getId()) {
            $this->addFlash('error', 'You cannot start a conversation with yourself');
            return $this->redirectToRoute('app_profile', ['username'=>($this->getUser()->getUsername())]);
        }

        // Check if conversation already exists
        $conversationID = $entityManager->getRepository(Conversation::class)->findConversationIdByParticipants($otherUser->getId(), $this->getUser()->getId());

        //redirect me to the already-existing conversation
        if ($conversationID) {
            return $this->redirectToRoute('app_chat', ['id'=> $conversationID]);
        }


        //create new conversation if it does not exist yet
        $conversation = new Conversation();


        //add myself as participant
        $participant = new Participant();
        $participant->setUser($this->getUser());
        $participant->setConversation($conversation);

        //add the other user as a participant to the same conversation
        $otherParticipant = new Participant();
        $otherParticipant->setUser($otherUser);
        $otherParticipant->setConversation($conversation);

        //persist data to database
        $entityManager->getRepository(Conversation::class)->save($conversation, true);
        $entityManager->getRepository(Participant::class)->save($participant, true);
        $entityManager->getRepository(Participant::class)->save($otherParticipant, true);

        //redirect to the new conversation page
        return $this->redirectToRoute('app_chat', ['id' => $conversation->getId()]);
    }
}
