<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfilParticipantController extends AbstractController
{
    /**
     * @Route("/profilParticipant", name="app_profilParticipant")
     */
    public function index(): Response
    {
        return $this->render('profil_participant/index.html.twig', [
            'controller_name' => 'MonProfilController',
        ]);
    }
}
