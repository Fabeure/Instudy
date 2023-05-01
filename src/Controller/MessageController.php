<?php

namespace App\Controller;

use App\Entity\Conversation;
use App\Entity\Message;
use App\Entity\User;
use App\Repository\MessageRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MessageController extends AbstractController
{

    const ATTRIBUTES_TO_SERIALIZE = ['id', 'content', 'createdAt', 'mine'];

    #[Route('/getMessages/{id}', name: 'app_getMessages')]
    public function index(Request $request, Conversation $conversation, MessageRepository $messageRepository): Response
    {
        // can i view the conversation

        $this->denyAccessUnlessGranted('view', $conversation);

        $messages = $messageRepository->findMessageByConversationId($conversation->getId());
        array_map(function ($message) {
            $message->setMine(
                $message->getUser()->getId() === $this->getUser()->getId()
                    ? true : false
            );
        }, $messages);


        return $this->json($messages, Response::HTTP_OK, [], [
            'attributes' => self::ATTRIBUTES_TO_SERIALIZE
        ]);
    }

    /**
     * @throws OptimisticLockException
     * @throws \Doctrine\DBAL\Exception
     * @throws ORMException
     */
    #[Route('/newMessage/', name: 'app_newMessage')]
    public function newMessage($id, Request $request, Conversation $conversation, EntityManagerInterface $entityManager)
    {
        $content = $request->request->get('content', null);
        $sender = $request->request->get('sender');
        $message = new Message();
        $message->setContent($content);
        $message->setUser($entityManager->getRepository(User::class)->findOneBy(['username' => $sender]));
        $message->setMine(true);

        // what conversation am i in ???
        $conversation->addMessage($message);
        $conversation->setLastMessage($message);

        $entityManager->getConnection()->beginTransaction();
        try {
            $entityManager->persist($message);
            $entityManager->persist($conversation);
            $entityManager->flush();
            $entityManager->commit();
        } catch (Exception $e) {
            $entityManager->rollback();
            throw $e;
        }

        return $this->redirectToRoute("app_home");
    }
}