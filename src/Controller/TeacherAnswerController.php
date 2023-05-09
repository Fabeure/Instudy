<?php

namespace App\Controller;

use App\Entity\Homework;
use App\Entity\Notification;
use App\Form\HomeworkFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\AssesHomeworkFormType;

class TeacherAnswerController extends AbstractController
{
    #[Route('/teacher/answer/', name: 'app_teacher_answer_student')]
    public function answerByStudent(EntityManagerInterface $entityManager, Request $request): Response
    {
        //handle access control
        if(!$this->isGranted('ROLE_TEACHER')){

            //add error flash message
            $this->addFlash('error', 'Only teachers can access this page.');

            //return to home
            return $this->redirectToRoute('app_home');
        }

        //fetch all homeworks that were sent to me
        $homeworks = $entityManager->getRepository(Homework::class)->findBy(['teacher' => $this->getUser()]);
        $forms = [];

        foreach ($homeworks as $index => $homework) {
            $forms[$index] = $this->createForm(AssesHomeworkFormType::class, $homework);
            $forms[$index]->handleRequest($request);

            if ($forms[$index]->isSubmitted() && $forms[$index]->isValid()) {
                // Update the homework properties
                $homework->setGrade($forms[$index]->get('grade')->getData());
                $homework->setCommentaire($forms[$index]->get('commentaire')->getData());

                // Create and save the notification
                $notification = new Notification();
                $entityManager->getRepository(Notification::class)->addNotification($notification, $this->getUser(), $homework->getStudent(), "Homework graded");

                $entityManager->getRepository(Notification::class)->save($notification, true);
                $entityManager->flush();

                $this->addFlash('success', 'Homework graded.');
            } elseif ($forms[$index]->isSubmitted() && !$forms[$index]->isValid()) {
                $this->addFlash('error', "Couldn't grade Homework.");
            }
        }

        $content = [];
        foreach ($homeworks as $index => $homework) {
            $content[$index]['homework'] = $homework;
            $content[$index]['form'] = $forms[$index]->createView();
        }

        return $this->render('teacher_answer/index.html.twig', [
            'contents' => $content
        ]);
    }

}
