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
        if(!($this->isGranted('ROLE_USER') and !$this->isGranted('ROLE_ADMIN'))){

            //add error flash message
            $this->addFlash('error', 'an admin cannot access this page.');

            //return to home
            return $this->redirectToRoute('app_profile', ['username' => $this->getUser()->getUsername()]);
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

            $entityManager->getRepository(Ticket::class)->save($ticket, true);
            $this->addFlash('success', 'Ticket sent, and admin will contact you soon.');
            return $this->redirectToRoute('app_ticket');
        }
        else if ($form->isSubmitted() && !$form->isValid()){
            $this->addFlash('error', 'Error sending ticket. Please try again');
            return $this->redirectToRoute('app_ticket');
        }

        return $this->render('Ticket/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
