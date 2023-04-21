<?php

namespace App\Controller;

use App\Entity\RegisteredEmails;
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
    public function index(Request $request,EntityManagerInterface $entityManager): Response
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
        foreach($fetched_emails as $email){

            if($email->isActif()){

                //get user email
                $user_email = $email->getEmail();

                //get user by email
                $user_instance = $entityManager->getRepository(User::class)->findOneBy(['email'=>$user_email]);

                //add user to array
                $users[$i]= [
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
                $i +=1;
            }

        }




        $demandes = [
            $d1 = [
                'id' => '1',
                'username' => 'User 1',
                'date' => '2021-05-01',
                'title' => 'Demande 1',
            ],
            $d2 = [
                'id' => '2',
                'username' => 'User 2',
                'date' => '2021-05-02',
                'title' => 'Demande 2',
            ],
        ];


        return $this->render('admin_pannel/index.html.twig', [
            'controller_name' => 'AdminPanelController',
            'data' => [
                'users' => $users,
                'demandes' => $demandes,
            ],
            'PreRegisterForm' => $form->createView()
        ]);
    }
}
