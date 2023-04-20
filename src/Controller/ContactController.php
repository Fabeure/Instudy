<?php

// src/Controller/ContactController.php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{

    /**
     * @throws TransportExceptionInterface
     */
    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request, MailerInterface $mailer, EntityManagerInterface $entityManager): Response
    {

        if ($this->getUser()) {
            $id = $this->getUser()->getId();
            $user = $entityManager->getRepository(User::class)->find($id);
            $defaultData = ['message' => 'Type your message here'];
            $form = $this->createFormBuilder($defaultData)
                ->add('subject', TextType::class)
                ->add('content', TextType::class)
                ->getForm();
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $email = (new Email())
                    ->from($user->getEmail())
                    ->to('sazaouzi@gmail.com')
                    ->subject($form->get('subject')->getData())
                    ->text($form->get('content')->getData());
                $mailer->send($email);
            }
        } else {
            $defaultData = ['message' => 'Type your message here'];
            $form = $this->createFormBuilder($defaultData)
                ->add('email', TextType::class)
                ->add('subject', TextType::class)
                ->add('content', TextType::class)
                ->getForm();
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $email = (new Email())
                    ->from($form->get('email')->getData())
                    ->to('sazaouzi@gmail.com')
                    ->subject($form->get('subject')->getData())
                    ->text($form->get('content')->getData());
                $mailer->send($email);
            }
        }


        return $this->render('contact/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
