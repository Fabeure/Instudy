<?php

namespace App\Controller;

use App\Entity\Cours;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CourseController extends AbstractController
{
    #[Route('/course/{courseName}', name: 'app_course')]
    public function index($courseName, EntityManagerInterface $entityManager): Response
    {

        $course = $entityManager->getRepository(Cours::class)->findOneBy(['courseName' => $courseName]);


        return $this->render('course/index.html.twig', [
            'cours' => $course
        ]);
    }
}
