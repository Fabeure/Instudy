<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Form\TicketFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Ticket;



class TicketController extends AbstractController
{
    #[Route('/ticket', name: 'app_ticket')]
    public function index(Request $request, EntityManagerInterface $entityManager)
    {

        //handle access control
        if(!$this->isGranted('ROLE_USER')){

            //add error flash message
            $this->addFlash('error', 'Login to access this page.');

            //return to home
            return $this->redirectToRoute('app_home');
        }


        $form = $this->createForm(TicketFormType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $ticket = new Ticket();

            $user = $this->getUser()->getId();

            $ticket->setAuthorID($user);
            $ticket->setTitle($form->get('Object')->getData());
            $ticket->setDescription($form->get('Description')->getData());
            $ticket->setDate(new \DateTime());

            $entityManager->persist($ticket);
            $entityManager->flush();

            return $this->redirectToRoute('app_ticket');
        }

        return $this->render('Ticket/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
