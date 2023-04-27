<?php

namespace App\Controller;

use App\Entity\RegisteredEmails;
use App\Entity\Ticket;
use App\Entity\User;
use App\Form\PreRegisterFormType;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\PseudoTypes\True_;
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
        if(!$this->isGranted('ROLE_ADMIN')){

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
        if ($form->isSubmitted() && $form->isValid()){

            //set fields for new user email

            $email->setEmail($form->get('email')->getData());
            $email->setActif(false);

            //save email to database
            $entityManager->getRepository(RegisteredEmails::class)->save($email, true);

            //add success message
            $this->addFlash('success', 'User added successfully');

            //clear form ????
            $entity = new RegisteredEmails();
            $form = $this->createForm(PreRegisterFormType::class, $entity);
        }


        //fetch all users and pass them to view
            $users = [];
            $i = 0;
            $fetched_emails = $entityManager->getRepository(RegisteredEmails::class)->findAllActiveFirst();

        //get user fields to show
        foreach ($fetched_emails as $email) {

            if($email->isActif()){

                //get user email
                $user_email = $email->getEmail();

                //get user by email
                $user_instance = $entityManager->getRepository(User::class)->findOneBy(['email'=>$user_email]);

                //add user to array
                $users[$i]= [
                    'id' => $user_instance->getId(),
                    'isActive' => true,
                    'username' => $user_instance->getUsername()
                ];
                $i +=1;
            }


            else{
                //add user to array
                $users[$i]= [
                    'email' => $email->getEmail(),
                    'isActive' => false
                ];
                $i += 1;
            }
        }

        //fetch all tickets and pass them to view
        $fetched_tickets = $entityManager->getRepository(Ticket::class)->findAll();
        $tickets = [];
        $i = 0;




        //assign each ticket value to array
        foreach ($fetched_tickets as $ticket){

            //fetch user that made the ticket by using the AuthorID field
            $author = $entityManager->getRepository(User::class)->find($ticket->getAuthorID());

            $tickets[$i] =[
                'id' => $ticket->getId(),
                'username' => $author->getUsername(),
                'date' => $ticket->getDate(),
                'title' => $ticket->getTitle(),
                'description' => $ticket->getDescription()
            ];
            $i += 1;
        }

        return $this->render('admin_pannel/index.html.twig', [
            'controller_name' => 'AdminPanelController',
            'data' => [
                'users' => $users,
                'demandes' => $tickets,
            ],
            'PreRegisterForm' => $form->createView()
        ]);
    }

    // remove a user from the user database and deactivate his email from the pre-registration database
    #[Route('/adminPanel/removeUser/{id}', name: 'app_remove_user')]
    public function removeUser($id, EntityManagerInterface $entityManager): Response
    {
        //handle access control
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'User tried to access a page without having ROLE_ADMIN');

        //get user by id
        $user = $entityManager->getRepository(User::class)->find($id);

        //get email by user email
        $email = $entityManager->getRepository(RegisteredEmails::class)->findOneBy(['email' => $user->getEmail()]);

        //remove user
        $entityManager->remove($user);
        $entityManager->flush();

        //deactivate email
        $email->setActif(false);

        //save modifications to email
        $entityManager->getRepository(RegisteredEmails::class)->save($email, true);

        //reroute to adminPanel
        return $this->redirectToRoute('app_admin_panel');
    }

    //app_remove_dem
    #[Route('/adminPanel/removeDem/{id}', name: 'app_remove_dem')]
    public function removeDem($id, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'User tried to access a page without having ROLE_ADMIN');


        //get ticket by id
        $ticket = $entityManager->getRepository(Ticket::class)->find($id);

        //remove ticket
        $entityManager->remove($ticket);
        $entityManager->flush();

        // $entityManager->flush();

        return $this->redirectToRoute('app_admin_panel');

    }
}
