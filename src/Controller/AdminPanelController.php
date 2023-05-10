<?php

namespace App\Controller;

use App\Entity\RegisteredEmails;
use App\Entity\Ticket;
use App\Entity\User;
use App\Form\PreRegisterFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminPanelController extends AbstractController
{
    #[Route('/adminPanel', name: 'app_admin_panel')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        //handle access control
        if (!$this->isGranted('ROLE_ADMIN')) {

            //add error flash message
            $this->addFlash('error', 'You need to be an admin to visit this page.');

            //return to home
            return $this->redirectToRoute('app_home');
        }

        //adding an email for pre-registration
        $email = new RegisteredEmails();

        //create pre-registration form to add user email to database
        $form = $this->createForm(PreRegisterFormType::class, $email);

        //handle form
        $form->handleRequest($request);

        //saving a new user to the pre-registration database
        if ($form->isSubmitted() && $form->isValid()) {

            //set fields for new user email

            $email->setEmail($form->get('email')->getData());
            $email->setActif(false);

            //save email to database
            $entityManager->getRepository(RegisteredEmails::class)->save($email, true);

            //add success message
            $this->addFlash('success', 'User added successfully');
        }


        //fetch all users and pass them to view
        $fetched_emails = $entityManager->getRepository(RegisteredEmails::class)->findAllActiveFirst();
        foreach ($fetched_emails as $ignored) {

            $users = array_map(function ($email) use ($entityManager) {
                if (!$email->isActif()) {
                    return [
                        'email' => $email->getEmail(),
                        'isActive' => false
                    ];
                }

                $user = $entityManager->getRepository(User::class)->findOneBy(['email' => $email->getEmail()]);

                return [
                    'id' => $user->getId(),
                    'isActive' => true,
                    'username' => $user->getUsername()
                ];
            }, $fetched_emails);

        }


        //fetch all tickets and pass them to view
        $fetched_tickets = $entityManager->getRepository(Ticket::class)->findAll();
        $tickets = array_map(function ($ticket) use ($entityManager) {

            //fetch user that made the ticket by using the AuthorID field
            $author = $entityManager->getRepository(User::class)->find($ticket->getAuthorID());
            return [
                'id' => $ticket->getId(),
                'username' => $author->getUsername(),
                'date' => $ticket->getDate()->format('d-m-y'),
                'title' => $ticket->getTitle(),
                'description' => $ticket->getDescription()
            ];
        }, $fetched_tickets);


        //render template and pass parametres
        return $this->render('admin_pannel/index.html.twig', [
            'controller_name' => 'AdminPanelController',
            'data' => [
                'users' => $users,
                'demandes' => $tickets,
            ],
            'PreRegisterForm' => $form->createView()
        ]);
    }

}
