<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\Participant;
use App\Entity\Sortie;
use App\Form\SortieType;
use App\Repository\EtatRepository;
use App\Repository\ParticipantRepository;
use App\Repository\SortieRepository;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/sortie", name="app_sortie_")
 */
class SortieController extends AbstractController
{
    private $sortieRepo;
    private $villeRepo;
    private $etatRepo;
    private $participantRepo;

    function __construct(SortieRepository $sortieRepo,VilleRepository $villeRepo,EtatRepository $etatRepo,ParticipantRepository $participantRepo)
    {
        $this->sortieRepo = $sortieRepo;
        $this->villeRepo = $villeRepo;
        $this->etatRepo = $etatRepo;
        $this->participantRepo = $participantRepo;
    }


    /**
     * @Route("/ajout", name="ajout")
     */
    public function index(Request $request): Response
    {
        $sortie = new Sortie();
        $sortieForm = $this->createForm(SortieType::class, $sortie);
        $sortieForm->handleRequest($request);

        $villes = $this->villeRepo->findAll();

        if($sortieForm->isSubmitted() && $sortieForm->isValid()){
            $etat = new Etat();
            $id = 1;
            $etat = $this->etatRepo->find($id);
            $sortie->setEtat($etat);

            $organisateur = new Participant();
            $idP = $this->getUser();
            $organisateur = $this->participantRepo->find($idP);

            $sortie->setOrganisateur($organisateur);

            try {
                $this->sortieRepo->add($sortie);
            } catch (OptimisticLockException $e) {
            } catch (ORMException $e) {
            }
        }
        return $this->render('sortie/index.html.twig', [
            'sortieForm' => $sortieForm->createView(),
            'villes'=>$villes,
        ]);
    }
    /**
     * @Route("/afficher/{id}", name="afficher")
     */
    public function afficherSortie($id,Request $request): Response
    {
       $sortie =  $this->sortieRepo->find($id);

       $participants =  $sortie->getParticipants();

        return $this->render('sortie/afficher.html.twig', [
            'sortie'=>$sortie,
            'participants' => $participants
        ]);
    }
    /**
     * @Route("/inscription/{id}", name="inscription")
     */
    public function inscriptionSortie($id,Request $request,EntityManagerInterface $em): Response
    {
        $participant = new Participant();
        $idP = $this->getUser();
        $participant = $this->participantRepo->find($idP);
        $sortie = $this->sortieRepo->find($id);
        $participant->addInscription($sortie);

        $em->flush();
        return $this->redirectToRoute('app_main');
    }

}
