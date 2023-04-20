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
            $this->addFlash('error', 'You need to be an admin to visit this page.');
            return $this->redirectToRoute('app_profile_settings');
        }

        //adding an email for pre-registration
        $email = new RegisteredEmails();
        $form = $this->createForm(PreRegisterFormType::class, $email);
        $form->handleRequest($request);

        //adding a new user to the pre-registration database
        if ($form->isSubmitted() && $form->isValid()){
            $email->setEmail($form->get('email')->getData());
            $email->setActif(false);
            $entityManager->persist($email);
            $entityManager->flush();
            $entity = new RegisteredEmails();
            $form = $this->createForm(PreRegisterFormType::class, $entity);
        }


        //fetch all users and pass them to view
        $users = [];
        $i = 0;
        $fetched_emails = $entityManager->getRepository(RegisteredEmails::class)->findAll();
        rsort($fetched_emails); //to show active users first

        //get user fields to show
        foreach($fetched_emails as $email){

            if($email->isActif()){
                //get user by email
                $user_instance = $entityManager->getRepository(User::class)->findOneBy(['email'=>$email->getEmail()]);
                $users[$i]= [
                    'isActive' => true,
                    'username' => $user_instance->getUsername()
                ];
                $i +=1;
            }


            else{
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
