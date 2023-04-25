<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ProfileSettingsFormType;
use App\Form\ProfileSettingsPasswordFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class ProfileSettingsController extends AbstractController
{
    #[Route('/profile/settings', name: 'app_profile_settings')]
    public function index(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        //handle access control
        if(!$this->isGranted('ROLE_USER')){

            //add error message for unauthorised access
            $this->addFlash('error', 'Login to access this page.');

            //return to home
            return $this->redirectToRoute('app_home');
        }


        //get current user
        $user = $this->getUser();

        //create info form
        $form = $this->createForm(ProfileSettingsFormType::class, $user);

        //create password change form
        $form2 = $this->createForm(ProfileSettingsPasswordFormType::class, $user);

        //handle forms
        $form->handleRequest($request);
        $form2->handleRequest($request);


        //modify user credentials
        if ($form->isSubmitted() && $form->isValid()) {

            //set new user fields
            $user->setUsername($form->get('username')->getData());
            $user->setName($form->get('name')->getData());
            $user->setSurname($form->get('surname')->getData());
            $user->setPhone($form->get('phone')->getData());
            $user->setBio($form->get('bio')->getData());
            $user->setPersonalEmail($form->get('personalEmail')->getData());

            //save user
            $entityManager->getRepository(User::class)->save($user, true);

            //add success message
            $this->addFlash('success', 'Your profile has been updated.');

            //return to home
            return $this->redirectToRoute('app_home');
        }

        //modify password
        if ($form2->isSubmitted() && $form2->isValid()){
            //check if old password is correct
            if (!$userPasswordHasher->isPasswordValid($user, $form2->get('OldPlainPassword')->getData())){
                //add error flash message

                $this->addFlash('error', 'Wrong password.');
            }
            else {

                //set new password
                $user->setPassword($userPasswordHasher->hashPassword($user, $form2->get('NewPlainPassword')->getData()));

                //save user
                $entityManager->getRepository(User::class)->save($user, true);

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
