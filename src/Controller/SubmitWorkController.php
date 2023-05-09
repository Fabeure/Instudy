<?php

namespace App\Controller;

use App\Entity\Cours;
use App\Entity\Homework;
use App\Entity\Matiere;
use App\Entity\Notification;
use App\Entity\User;
use App\Form\CourseFormType;
use App\Form\HomeworkFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Routing\Annotation\Route;

class SubmitWorkController extends AbstractController
{
    #[Route('/submit', name: 'app_submit_work')]
    public function index(Request $request, EntityManagerInterface $entityManager,HubInterface $hub): Response
    {
        //handle access control
        if(!$this->isGranted('ROLE_USER')){

            //add error flash message
            $this->addFlash('error', 'Login to access this page.');

            //return to home
            return $this->redirectToRoute('app_home');
        }

        //create a new homework entry
        $homework = new Homework();

        //handle the form for course submittion
        $form = $this->createForm(HomeworkFormType::class, $homework);
        $form->handleRequest($request);

        //get form data and persist course to database
        if ($form->isSubmitted() && $form->isValid()){
            //write homework fields
            $homework->setTeacher($form->get('teacher')->getData());
            $homework->setStudent($this->getUser());
            $homework->setCommentaire($form->get('commentaire')->getData());
            $homework->setHomeworkFile($form->get('homeworkFile')->getData());

            //create new notification
            $notif = new Notification();


            //add new notification
            $entityManager->getRepository(Notification::class)->addNotification($notif, $this->getUser(), $form->get('teacher')->getData(), "New Homework", $hub);

            //persist everything to database
            $entityManager->getRepository(Homework::class)->save($homework, true);
            $entityManager->getRepository(Notification::class)->save($notif, true);
            $this->addFlash('success', 'Homework sent.');
        }

        //if there is an uploading error:
        else if ($form->isSubmitted() && !$form->isValid()){
            $this->addFlash('error', "Couldn't send Homework.");
        }

        //get my submitted work
        $uploaded = $entityManager->getRepository(Homework::class)->findBy(['student'=>$this->getUser()]);
        $homeworks = array_map(function($homework) use($entityManager) {
            $name = "";
            $matiere = $entityManager->getRepository(Matiere::class)->findOneBy(['teacher'=>$homework->getTeacher()]);
            return [
                'matiere' => $matiere->getMatiereName(),
                'commentaire' => $homework->getCOmmentaire(),
                'homeworkName' => $homework->getHomeworkName()
            ];
        }, $uploaded);


        return $this->render('submit_work/index.html.twig', [
            'HomeworkForm' => $form->createView(),
            'homeworks' => $homeworks
        ]);
    }
}
