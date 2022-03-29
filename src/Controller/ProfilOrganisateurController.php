<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfilOrganisateurController extends AbstractController
{
    /**
     * @Route("/profilOrganisateur", name="app_profilOrganisateur")
     */
    public function index(): Response
    {
        return $this->render('profil_organisateur/index.html.twig', [
            'controller_name' => 'MonProfilController',
        ]);
    }
}
