<?php

// src/Controller/ContactController.php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
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
        //creating the email form
        $defaultData = ['message' => 'Type your message here'];
        $form = $this->createFormBuilder($defaultData)
            ->add('email', EmailType::class)
            ->add('subject', TextType::class)
            ->add('content', TextType::class)
            ->getForm();
        $form->handleRequest($request);

        if ($this->getUser()){
            if ($form->isSubmitted() && $form->isValid()) {
                //create the email object
                $email = (new Email())
                    ->from($this->getUser()->getEmail())
                    ->to('sazaouzi@gmail.com')
                    ->subject($form->get('subject')->getData())
                    ->text($form->get('content')->getData());
                //send email, needs fixing
                $mailer->send($email);
            }
        }
        else{
            if ($form->isSubmitted() && $form->isValid()) {
                //create the email object
                $email = (new Email())
                    ->from($form->get('email')->getData())
                    ->to('sazaouzi@gmail.com')
                    ->subject($form->get('subject')->getData())
                    ->text($form->get('content')->getData());
                //send email, needs fixing
                $mailer->send($email);
            }
        }


        return $this->render('contact/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
