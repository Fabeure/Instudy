<?php

namespace App\Controller;

use App\Entity\Conversation;
use App\Entity\Participant;
use App\Entity\User;
use App\Repository\ConversationRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\WebLink\Link;

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
        if ($conversationID) {
            return $this->redirectToRoute('app_chat', ['id'=> $conversationID]);
        }


        //create new conversation
        $conversation = new Conversation();


        //add myself as participant
        $participant = new Participant();
        $participant->setUser($this->getUser());
        $participant->setConversation($conversation);

        //add the other user as a participant to the same conversation
        $otherParticipant = new Participant();
        $otherParticipant->setUser($otherUser);
        $otherParticipant->setConversation($conversation);

        //persis data to database
        $entityManager->persist($conversation);
        $entityManager->persist($participant);
        $entityManager->persist($otherParticipant);
        $entityManager->flush();
        $entityManager->commit();

        //redirect to the conversation page
        return $this->redirectToRoute('app_chat', ['id' => $conversation->getId()]);
    }

    //what is this ??
    /*
    #[Route('/getConversations', name: 'app_getConversations')]
    public function getConvs(ConversationRepository $conversationRepository, Request $request): Response
    {
        $conversations = $conversationRepository->findConversationsByUser($this->getUser()->getId());

        $hubUrl = $this->getParameter('mercure.default_hub');

        $this->addLink($request, new Link('mercure', $hubUrl));
        return $this->json($conversations);
    }

    */
}
