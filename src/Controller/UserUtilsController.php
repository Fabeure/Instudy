<?php

namespace App\Controller;

use App\Entity\RegisteredEmails;
use App\Entity\Ticket;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserUtilsController extends AbstractController
{
    // remove a user from the user database and deactivate his email from the pre-registration database
    #[Route('/adminPanel/removeUser/{id}', name: 'app_remove_user')]
    public function removeUser($id, EntityManagerInterface $entityManager): Response
    {
        //handle access control
        if (!$this->isGranted('ROLE_ADMIN')) {

            //add error flash message
            $this->addFlash('error', 'You need to be an admin to visit this page.');

            //return to profile
            return $this->redirectToRoute('app_profile', ['username' => $this->getUser()->getUsername()]);
        }

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
        //handle access control
        if (!$this->isGranted('ROLE_ADMIN')) {

            //add error flash message
            $this->addFlash('error', 'You need to be an admin to visit this page.');

            //return to profile
            return $this->redirectToRoute('app_profile', ['username' => $this->getUser()->getUsername()]);
        }


        //get ticket by id
        $ticket = $entityManager->getRepository(Ticket::class)->find($id);

        //remove ticket
        $entityManager->remove($ticket);
        $entityManager->flush();

        // $entityManager->flush();

        return $this->redirectToRoute('app_admin_panel');

    }
    #[Route('/adminPanel/makeTeacher/{id}', name: 'app_make_teacher')]
    public function makeTeacher($id, EntityManagerInterface $entityManager){

        //handle access control
        if (!$this->isGranted('ROLE_ADMIN')) {

            //add error flash message
            $this->addFlash('error', 'You need to be an admin to visit this page.');

            //return to profile
            return $this->redirectToRoute('app_profile', ['username' => $this->getUser()->getUsername()]);
        }

        //get the user that we want to mark as a teacher
        $user = $entityManager->getRepository(User::class)->findOneBy(["id"=>$id]);

        //set user's role as teacher
        $user->setRoles(['ROLE_TEACHER']);

        //save user
        $entityManager->getRepository(User::class)->save($user, true);

        //return to admin Panel
        return $this->redirectToRoute('app_admin_panel');
    }

}
