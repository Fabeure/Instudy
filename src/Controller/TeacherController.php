<?php

namespace App\Controller;

use App\Entity\Cours;
use App\Entity\Matiere;
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

        //create a new course
        $course = new Cours();

        //handle the form for course submittion
        $form = $this->createForm(CourseFormType::class, $course);
        $form->handleRequest($request);

        //get form data and persist course to database
        if ($form->isSubmitted() && $form->isValid()){

            //get the subject the teacher teaches
            $matiere = $entityManager->getRepository( Matiere::class)->findOneBy(['teach_id'=>$this->getUser()->getId()]);

            //write course fields
            $course->setCourseFile($form->get('courseFile')->getData());
            $course->setNom($form->get('nom')->getData());
            $course->setTeacher($this->getUser());
            $course->setMatiere($matiere);

            //save course
            $entityManager->getRepository(Cours::class)->save($course, true);
            }
        return $this->render('teacher/index.html.twig', [
            'CourseForm' => $form->createView()
        ]);
    }
}
