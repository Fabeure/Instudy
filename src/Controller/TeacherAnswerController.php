<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TeacherAnswerController extends AbstractController
{
    #[Route('/teacher/answer/{student}', name: 'app_teacher_answer_student')]
    public function answerByStudent(student  $student ): Response
    {
        return $this->render('teacher_answer/index.html.twig', [
            'student' => $student ,
        ]);
    }

    #[Route('/teacher/answer', name: 'app_teacher_answer')]
    public function answer(): Response
    {
        return $this->render('teacher_answer/index.html.twig', [
            'student' => 'student',
        ]);
    }
}
