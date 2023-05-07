<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Course;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UserRepository;
use App\Repository\CourseRepository;

class CourseController extends AbstractController
{
    

    #[Route('/course', name: 'app_create_course')]
    public function createCourse(EntityManagerInterface $entityManager, UserRepository $userRepository): Response
    {
        $course = new Course();
        $course->setTitle('Maths');
        $course->setPdf('maths.pdf');

        //get authenticated user
        $user = $userRepository->getUser();
        $course->setIdProf($user->getId());

        $entityManager->persist($course);
        $entityManager->flush();

        return $this->render('hub/index.html.twig');
    }

    #[Route('/course/{id}', name: 'app_course')]
    public function getCourse($id, CourseRepository $courseRepository): Response
    {
        $course = $courseRepository->findOneBy(['id' => $id]);

        //if there's no user with this username
        if (!$course) {
            $this->addFlash('error', $course . ' course does not exist !');
            return $this->render('home/index.html.twig');
        } else {
            return $this->render('course/index.html.twig', [
                'course' => $course,
                'pdf' => "asset('assets/pdf/".$course->getTitle()."pdf')"
            ]);
        }
    }
    

    #[Route('/course/add', name: 'app_add_course')]
    public function addCoursePage(): Response
    {
        return $this->render('course/add.html.twig', [
            'controller_name' => 'CourseController',
        ]);
    }
}
