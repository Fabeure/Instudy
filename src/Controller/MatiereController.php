<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Matiere;
use App\Entity\Course;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CourseRepository;
use Symfony\Component\HttpFoundation\Request;

class MatiereController extends AbstractController
{


    #[Route('/matiere/', name: 'app_create_matiere')]
    public function createMatiere(Request $request, EntityManagerInterface $entityManager): Response
    {
        $name = $request->request->get('name');

        $test = $entityManager->getRepository(Matiere::class)->findOneBy(['name' => $name]);
        if($test){
            $this->addFlash('error', 'This subject already exists !');
            return $this->render('matiere/add.html.twig');
        }

        $matiere = new Matiere();
        $matiere->setName($name);
        
        $entityManager->persist($matiere);
        $entityManager->flush();

        return $this->render('matiere/index.html.twig', [
            'controller_name' => 'MatiereController',
        ]);
    }

    #[Route('/matieres/', name: 'app_matieres')]
    public function matieres(EntityManagerInterface $entityManager): Response
    {
        $matieres = $entityManager->getRepository(Matiere::class)->findAll();

        // if there is no course 
        if (!$matieres) {
            $this->addFlash('error', 'There is no subject !');
            return $this->render('home/index.html.twig');
        } else {
            return $this->render('matiere/all.html.twig', [
                'matieres' => $matieres
            ]);
        }
    }

    #[Route('/matiere/add', name: 'app_add_matiere')]
    public function addMatierePage()
    {
        return $this->render('matiere/add.html.twig', [
            'controller_name' => 'CourseController',
        ]);
    }

    #[Route('/matiere/{id}', name: 'app_matiere')]
    public function index($id, EntityManagerInterface $entityManager): Response
    {
        
        $courses = $entityManager->getRepository(Course::class)->findBy(['idMatiere' => $id]);

        // if there is no course 
        if (!$courses) {
            $this->addFlash('error', 'There is no course for this subject !');
            return $this->render('home/index.html.twig');
        } else {
            return $this->render('matiere/index.html.twig', [
                'courses' => $courses,
                'matiere' => $entityManager->getRepository(Matiere::class)->findById($id)
            ]);
        }
    }

    
}
