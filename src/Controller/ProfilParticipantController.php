<?php

namespace App\Controller;
use App\Repository\ParticipantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfilParticipantController extends AbstractController
{
    private $participantRepo;
    
    
    function __construct(ParticipantRepository $participantRepo)  //injection de dépendances
    {
        $this->participantRepo = $participantRepo;
    }



   
    /**
     * @Route("/profilParticipant/{id}", name="app_profilParticipant")
     */
    public function index($id): Response
    {
        if($this->getUser()){

            $organisateur= $this->participantRepo->find($id);

            return $this->render('profil_participant/index.html.twig',compact("organisateur"));
        }
        return $this->redirectToRoute('app_logout');
    }
}
