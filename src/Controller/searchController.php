<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class searchController extends AbstractController
{
    #[Route('/newMessage', name: 'app_newMessage')]
    public function newMessage(Request $request, EntityManagerInterface $entityManager)
    {
        //get contents of request
        $input = $request->request->get('searchQuery ', null);
        echo 'test ajax ';
        $this->addFlash("success", 'test ajax');
    }
}