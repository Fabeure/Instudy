<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileSettingsController extends AbstractController
{
    #[Route('/profile/settings', name: 'app_profile_settings')]
    public function index(): Response
    {
        return $this->render('profile_settings/index.html.twig', [
            'controller_name' => 'ProfileSettingsController',
        ]);
    }
}
