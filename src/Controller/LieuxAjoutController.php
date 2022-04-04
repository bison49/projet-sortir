<?php

namespace App\Controller;

use App\Form\LieuxType;
use App\Form\RechercherParSaisieTexteForm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LieuxAjoutController extends AbstractController
{
    /**
     * @Route("/lieux/ajout", name="app_lieux_ajout")
     */
    public function index(): Response
    {

        $form = $this->createForm(LieuxType::class);


        return $this->renderForm('lieux_ajout/index.html.twig', [
            'controller_name' => 'LieuxAjoutController', "LieuxType"=>$form
        ]);
    }
}
