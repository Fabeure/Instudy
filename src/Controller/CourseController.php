<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Course;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CourseRepository;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;


class CourseController extends AbstractController
{
    #[Route('/course/', name: 'app_create_course')]
    public function createCourse(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $title = $request->request->get('title');
        $pdf = $request->files->get('pdf');
        if ($pdf) {
            $originalFilename = pathinfo($pdf->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename.'-'.uniqid().'.'.$pdf->guessExtension();

            // Move the file to a directory where PDFs should be stored
            try {
                $pdf->move(
                    $this->getParameter('pdf_directory'),
                    $newFilename
                );
            } catch (FileException $e) {
                // Handle the exception
            }
        }
        

        $course = new Course();
        $course->setTitle($title);
        $course->setPdf($newFilename);

        $course->setIdProf($entityManager->getRepository(User::class)->getUser()->getId());

        $entityManager->persist($course);
        $entityManager->flush();

        return $this->redirectToRoute('app_course', ['id' => $course->getId()]);
    }

    #[Route('/course/add', name: 'app_add_course')]
    public function addCoursePage()
    {
        return $this->render('course/add.html.twig', [
            'controller_name' => 'CourseController',
        ]);
    }

    #[Route('/course/{id}', name: 'app_course')]
    public function getCourse($id, CourseRepository $courseRepository): Response
    {
        $course = $courseRepository->findOneBy(['id' => $id]);

        // Get the path to the PDF file
        $pdfPath = $this->getParameter('pdf_directory').'/'.$course->getPdf();

        //if there's no user with this username
        if (!$course) {
            $this->addFlash('error', $course . ' course does not exist !');
            return $this->render('home/index.html.twig');
        } else {
            return $this->render('course/index.html.twig', [
                'course' => $course,
                'pdf' => $pdfPath,
            ]);
        }
    }

    
}