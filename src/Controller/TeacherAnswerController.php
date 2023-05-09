<?php

namespace App\Controller;

use App\Entity\Homework;
use App\Entity\Matiere;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TeacherAnswerController extends AbstractController
{
    #[Route('/teacher/answer/', name: 'app_teacher_answer_student')]
    public function answerByStudent(EntityManagerInterface $entityManager): Response
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


        return $this->render('teacher_answer/index.html.twig', [
            'homeworks' => $homeworks
        ]);
    }

}
