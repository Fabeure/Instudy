<?php

namespace App\Controller;

use App\Entity\Notification;
use App\Entity\Ticket;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Annotation\Route;

class NotificationController extends AbstractController
{
    #[Route('/removeNotification', name: 'app_remove_notif')]
    public function removeDem(EntityManagerInterface $entityManager, Request $request): Response
    {
        //handle access control
        if(!$this->isGranted('ROLE_USER')){

            //add error flash message
            $this->addFlash('error', 'Login to access this page.');

            //return to profile
            return $this->redirectToRoute('app_profile', ['username' => $this->getUser()->getUsername()]);
        }


        //get notification by id
        $id = $request->request->get('notificationID');

        //get notification
        $notification = $entityManager->getRepository(Notification::class)->findOneBy(['identifier' => $id]);

        if (!$notification){
            $notification = $entityManager->getRepository(Notification::class)->find($id);
        }

        //remove notification
        $entityManager->remove($notification);
        $entityManager->flush();


        return $this->redirectToRoute('app_admin_panel');

    }
}
