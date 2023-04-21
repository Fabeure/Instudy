<?php

namespace App\Controller;

use App\Entity\RegisteredEmails;
use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class RegistrationController extends AbstractController
{

    #[Route('/terms', name: 'app_terms')]
    public function showTerms(){
        return $this->render('terms.html.twig');
    }


    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {

        //create user instance and handle form request
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);



        //register user
        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form->get('email')->getData();


            //check if user is an admin
            if ($email == 'admin@admin.insat.ucar.tn'){
                //give user admin role
                $user->setRoles(['ROLE_ADMIN']);
                //hash password
                $user->setPassword($userPasswordHasher->hashPassword($user,$form->get('plainPassword')->getData()));
                //save user
                $entityManager->getRepository(User::class)->save($user, true);
                //add success message
                $this->addFlash('success', 'Admin created successfully');
                return $this->redirectToRoute('app_home');
            }



            //check if user is in pre-registration database or not
            $preRegisteredEmail = $entityManager->getRepository(RegisteredEmails::class)->findOneBy(['email' => $email]);



            //add error flash if user does not exist
            if(!$preRegisteredEmail){
                //empty form fields??
                $entity = new User();
                $form = $this->createForm(RegistrationFormType::class, $entity);
                $this->addFlash('error', 'This email has not been pre-registered.');
            }



            else{
                //make user active
                $preRegisteredEmail->setActif(true);
                // encode the plain password
                $user->setPassword($userPasswordHasher->hashPassword($user, $form->get('plainPassword')->getData()));
                //save user
                $entityManager->getRepository(User::class)->save($user, true);
                //add success flash message
                $this->addFlash('success', 'User created successfully');
                //redirect to home
                return $this->redirectToRoute('app_home');
            }


            // do anything else you need here, like send an email



        }
        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
