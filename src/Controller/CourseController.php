<?php

namespace App\Controller;

use App\Entity\Cours;
use App\Entity\Question;
use App\Form\QuestionFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CourseController extends AbstractController
{
    #[Route('/course/{courseName}', name: 'app_course')]
    public function index($courseName, EntityManagerInterface $entityManager, Request $request): Response
    {
        if(!$this->isGranted('ROLE_USER')){

            //add error flash message
            $this->addFlash('error', 'Login to access this page.');

            //return to home
            return $this->redirectToRoute('app_home');
        }
        //get course you are trying to see
        $course = $entityManager->getRepository(Cours::class)->findOneBy(['courseName' => $courseName]);

        //create a new question entry, in case one is asked
        $question = new Question();

        //create and handle question form
        $form = $this->createForm(QuestionFormType::class, $question);
        $form->handleRequest($request);

        //persist question to database
        if ($form->isSubmitted() && $form->isValid()){
            $question->setContent($form->get('content')->getData());
            $question->setSender($this->getUser());
            $question->setCourse($course);

            $entityManager->getRepository(Question::class)->save($question, true);
            $this->addFlash('success', 'Question sent, pending answer.');
        }
        else if ($form->isSubmitted() && !$form->isValid()){
            $this->addFlash('error', 'The Question could not be sent.');
        }

        $questions = $entityManager->getRepository(Question::class)->findBy(['cours' => $course]);


        return $this->render('course/index.html.twig', [
            'questions' => $questions,
            'cours' => $course,
            'QuestionForm' => $form->createView()
        ]);
    }

    #[Route('/course/remove/{courseName}', name: 'app_remove_course')]
    public function removeDem($courseName, EntityManagerInterface $entityManager): Response
    {
        //handle access control
        if(!$this->isGranted('ROLE_TEACHER')){

            //add error flash message
            $this->addFlash('error', 'Only teachers can access this page.');

            //return to home
            return $this->redirectToRoute('app_home');
        }


        //get course by id
        $course = $entityManager->getRepository(Cours::class)->findOneBy(['courseName' => $courseName]);

        //remove course from database
        $entityManager->remove($course);
        $entityManager->flush();


        return $this->redirectToRoute('app_teacher');

    }
}
