<?php

namespace App\Controller;

use App\Entity\Homework;
use App\Entity\Matiere;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ViewWorkController extends AbstractController
{
    #[Route('/view/work', name: 'app_view_work')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        //handle access control
        if(!$this->isGranted('ROLE_USER')){

            //add error flash message
            $this->addFlash('error', 'Login to access this page.');

            //return to home
            return $this->redirectToRoute('app_home');
        }
        $uploaded = $entityManager->getRepository(Homework::class)->findBy(['student'=>$this->getUser()]);

        $homeworks = array_map(function($homework) use($entityManager) {
            $name = "";
            $matiere = $entityManager->getRepository(Matiere::class)->findOneBy(['teacher'=>$homework->getTeacher()]);
            return [
                'matiere' => $matiere->getMatiereName(),
                'commentaire' => $homework->getCOmmentaire(),
                'homeworkName' => $homework->getHomeworkName(),
                'grade' => $homework->getGrade(),
            ];
        }, $uploaded);

            return $this->render('view_work/index.html.twig', [
                'homeworks' => $homeworks,
        ]);
    }
}
