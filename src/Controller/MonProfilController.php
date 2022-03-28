<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MonProfilController extends AbstractController
{
    /**
     * @Route("/profil", name="app_profil")
     */
    public function index(): Response
    {
        return $this->render('mon_profil/index.html.twig', [
            'controller_name' => 'MonProfilController',
        ]);
    }
}
