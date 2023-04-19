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
        $id = $this->getUser()->getId();
        $user = $entityManager->getRepository(User::class)->find($id);
        $form = $this->createForm(ProfileSettingsFormType::class, $user);
        $form2 = $this->createForm(ProfileSettingsPasswordFormType::class, $user);
        $form->handleRequest($request);
        $form2->handleRequest($request);



        if ($form->isSubmitted() && $form->isValid()) {
            $user->setUsername($form->get('username')->getData());
            $user->setName($form->get('name')->getData());
            $user->setSurname($form->get('surname')->getData());
            $user->setPhone($form->get('phone')->getData());
            $user->setBio($form->get('bio')->getData());
            $user->setPersonalEmail($form->get('personalEmail')->getData());
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('success', 'Your profile has been updated.');
            return $this->redirectToRoute('app_home');
        }
        if ($form2->isSubmitted() && $form2->isValid()){
            if (!$userPasswordHasher->isPasswordValid($user, $form2->get('OldPlainPassword')->getData())){
                return $this->redirectToRoute('app_register');
            }
            else {
                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $form2->get('NewPlainPassword')->getData()
                    )
                );
                $entityManager->persist($user);
                $entityManager->flush();
            }

        }

        return $this->render('profile_settings/index.html.twig', [
            'profileSettingsForm' => $form->createView(),
            'profileSettingsPasswordForm' => $form2->createView()
        ]);
    }
}
