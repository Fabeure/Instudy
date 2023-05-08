<?php

namespace App\Controller;

use App\Entity\Cours;
use App\Entity\Question;
use App\Form\QuestionFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Encoder\QpEncoder;
use Symfony\Component\Routing\Annotation\Route;

class CourseController extends AbstractController
{
    #[Route('/course/{courseName}', name: 'app_course')]
    public function index($courseName, EntityManagerInterface $entityManager, Request $request): Response
    {

        $course = $entityManager->getRepository(Cours::class)->findOneBy(['courseName' => $courseName]);
        $question = new Question();

        $form = $this->createForm(QuestionFormType::class, $question);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()){
            $question->setContent($form->get('content')->getData());
            $question->setSender($this->getUser());
            $question->setMatiere($course->getMatiere());

            $entityManager->getRepository(Question::class)->save($question, true);
        }
        return $this->render('course/index.html.twig', [
            'cours' => $course,
            'QuestionForm' => $form->createView()
        ]);
    }
}
