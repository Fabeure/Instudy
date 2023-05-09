<?php

namespace App\Controller;

use App\Entity\Cours;
use App\Entity\Matiere;
use App\Entity\Question;
use App\Form\CourseFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TeacherController extends AbstractController
{
    #[Route('/teacher', name: 'app_teacher')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        //handle access control
        if(!$this->isGranted('ROLE_TEACHER')){

            //add error flash message
            $this->addFlash('error', 'Only teachers can access this page.');

            //return to home
            return $this->redirectToRoute('app_home');
        }
        //create a new course
        $course = new Cours();

        //handle the form for course submittion
        $form = $this->createForm(CourseFormType::class, $course);
        $form->handleRequest($request);

        //get form data and persist course to database
        if ($form->isSubmitted() && $form->isValid()){

            //get the subject the teacher teaches
            $matiere = $entityManager->getRepository(Matiere::class)->findOneBy(['teacher'=>$this->getUser()->getId()]);

            //write course fields
            $course->setCourseFile($form->get('courseFile')->getData());
            $course->setNom($form->get('nom')->getData());
            $course->setTeacher($this->getUser());
            $course->setMatiere($matiere);

            //save course
            $entityManager->getRepository(Cours::class)->save($course, true);
            $this->addFlash('success', 'Course added.');
            }

        //if there is an uploading error:
        else if ($form->isSubmitted() && !$form->isValid()){
            $this->addFlash('error', "Couldn't add course.");
        }

        //get questions and pass them to view NEED TO MAKE QUERY THAT GETS ALL QUESTIONS BY TEACHER
        $questions = $entityManager->getRepository(Question::class)->findBy(['id' =>'5']);


        //get my courses and pass them to view
        $courses = $entityManager->getRepository(Cours::class)->findBy(['teacher'=>$this->getUser()]);

        return $this->render('teacher/index.html.twig', [
            'CourseForm' => $form->createView(),
            'questions' => $questions,
            'courses' => $courses
        ]);
    }
}
