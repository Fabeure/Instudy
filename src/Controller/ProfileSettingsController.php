<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ProfileSettingsFormType;
use App\Form\ProfileSettingsPasswordFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class ProfileSettingsController extends AbstractController
{
    #[Route('/profile/{username}/settings', name: 'app_profile_settings')]
    public function index($username , Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        //handle access control
        if(!$this->isGranted('ROLE_USER')) {

            //add error message for unauthorised access
            $this->addFlash('error', 'Login to access this page.');

            //return to home
            return $this->redirectToRoute('app_home');
        }


        //get current user
        $current_user = $this->getUser();



        //get current user id
        $my_id = $current_user->getID();


        //get visited user
        $visited = $entityManager->getRepository(User::class)->findOneBy(['username'=>$username]);

        //get visited user id
        $visited_id = $visited->getID();


        if ($visited_id != $my_id){
            //add error message for unauthorised access
            $this->addFlash('error', 'You are not allowed to access this page.');

            //return to home
            return $this->redirectToRoute('app_profile', array('username'=>($current_user->getUsername())));
        }




        //create info form
        $form = $this->createForm(ProfileSettingsFormType::class, $current_user);

        //create password change form
        $form2 = $this->createForm(ProfileSettingsPasswordFormType::class, $current_user);

        //handle forms
        $form->handleRequest($request);
        $form2->handleRequest($request);


        //modify user credentials
        if ($form->isSubmitted() && $form->isValid()) {

            //set new user fields
            $current_user->setUsername($form->get('username')->getData());
            $current_user->setName($form->get('name')->getData());
            $current_user->setSurname($form->get('surname')->getData());
            $current_user->setPhone($form->get('phone')->getData());
            $current_user->setBio($form->get('bio')->getData());
            $current_user->setPersonalEmail($form->get('personalEmail')->getData());
            $current_user->setImageFile($form->get('imageFile')->getData());

            //save user
            $entityManager->getRepository(User::class)->save($current_user, true);

            //add success message
            $this->addFlash('success', 'Your profile has been updated.');
            //return to home
            return $this->redirectToRoute('app_profile_settings', ['username'=>$current_user->getUsername()]);
        }

        //modify password
        if ($form2->isSubmitted() && $form2->isValid()){
            //check if old password is correct
            if (!$userPasswordHasher->isPasswordValid($current_user, $form2->get('OldPlainPassword')->getData())){
                //add error flash message

                $this->addFlash('error', 'Wrong password.');
            }
            else {

                //set new password
                $current_user->setPassword($userPasswordHasher->hashPassword($current_user, $form2->get('NewPlainPassword')->getData()));

                //save user
                $entityManager->getRepository(User::class)->save($current_user, true);

                //add success message
                $this->addFlash('success', 'Password updated successfully');
            }
        }

        return $this->render('profile_settings/index.html.twig', [
            'profileSettingsForm' => $form->createView(),
            'profileSettingsPasswordForm' => $form2->createView()
        ]);
    }
}
