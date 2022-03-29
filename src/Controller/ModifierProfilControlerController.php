<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Form\ModifParticipantType;
use App\Repository\ParticipantRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ModifierProfilControlerController extends AbstractController
{

    private $partiRepo;

    function __construct(ParticipantRepository $partiRepo)
    {
        $this->partiRepo = $partiRepo;
    }
    /**
     * @Route("/profil", name="app_profil")
     */
    public function index(ParticipantRepository $participantRepository, Request $request): Response
    {
        $user = new Participant();
        $formModif = $this->createForm(ModifParticipantType::class, $user);
        $formModif->handleRequest($request);

        if($formModif->isSubmitted()) {
            $this->partiRepo->add($user);

            return $this->redirectToRoute("app_modifier");
        }



        return $this->render("mon_profil/index.html.twig",
            ['formModif' => $formModif->createView()]);
    }
}