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
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\WebLink\Link;

class ConversationController extends AbstractController
{
    /**
     * @throws Exception
     */
    #[Route('/newConversations', name: 'app_newConversations')]
    public function index(Request $request, UserRepository $userRepository, ConversationRepository $conversationRepository, EntityManagerInterface $entityManager): JsonResponse
    {
        $otherUser = $request->get('otherUser', 0);
        $otherUser = $userRepository->find($otherUser);

        if (is_null($otherUser)) {
            throw new Exception("The user was not found");
        }

        // cannot create a conversation with myself
        if ($otherUser->getId() === $this->getUser()->getId()) {
            throw new Exception("That's deep but you cannot create a conversation with yourself");
        }

        // Check if conversation already exists
        $conversation = $conversationRepository->findConversationByParticipants(
            $otherUser->getId(),
            $this->getUser()->getId()
        );

        if (count($conversation)) {
            throw new Exception("The conversation already exists");
        }

        $conversation = new Conversation();

        $participant = new Participant();
        $participant->setUser($this->getUser());
        $participant->setConversation($conversation);


        $otherParticipant = new Participant();
        $otherParticipant->setUser($otherUser);
        $otherParticipant->setConversation($conversation);

        $entityManager->getConnection()->beginTransaction();
        try {
            $entityManager->persist($conversation);
            $entityManager->persist($participant);
            $entityManager->persist($otherParticipant);

            $entityManager->flush();
            $entityManager->commit();

        } catch (Exception $e) {
            $entityManager->rollback();
            throw $e;
        }


        return $this->json([
            'id' => $conversation->getId()
        ], Response::HTTP_CREATED, [], []);
    }
    #[Route('/getConversations', name: 'app_getConversations')]
    public function getConvs(ConversationRepository $conversationRepository, Request $request): JsonResponse
    {
        $conversations = $conversationRepository->findConversationsByUser($this->getUser()->getId());
        $hubUrl = $this->getParameter('mercure.default_hub');
        $this->addLink($request, new Link('mercure', $hubUrl));
        return $this->json($conversations);
    }
}
