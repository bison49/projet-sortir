<?php

namespace App\Controller;

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
use Symfony\Component\HttpFoundation\JsonResponse;
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

    function __construct(SortieRepository $sortieRepo, VilleRepository $villeRepo, EtatRepository $etatRepo, ParticipantRepository $participantRepo)
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
        if ($this->isGranted('ROLE_USER')) {

            $sortie = new Sortie();
            $sortieForm = $this->createForm(SortieType::class, $sortie);
            $sortieForm->handleRequest($request);

            $villes = $this->villeRepo->findAll();

            if ($sortieForm->isSubmitted() && $sortieForm->isValid()) {

                if ($request->request->get('enr')) {
                    $id = 1;
                } else {
                    $id = 2;
                }
                $sortie->setEtat($this->etatRepo->find($id));
                $sortie->setSiteOrganisateur($this->getUser()->getNoSite());
                $sortie->setOrganisateur($this->getUser());

                try {
                    $this->sortieRepo->add($sortie);
                } catch (OptimisticLockException $e) {
                } catch (ORMException $e) {
                }
                if ($id == 1) {
                    $this->addFlash('success', 'Votre sortie a été enregistrée');
                } else {
                    $this->addFlash('success', 'Votre sortie a été enregistrée et publiée');
                }

                return $this->redirectToRoute('app_sortie_ajout');
            }
            return $this->render('sortie/index.html.twig', [
                'sortieForm' => $sortieForm->createView(),
                'villes' => $villes,
            ]);
        }
        return $this->redirectToRoute('app_logout');
    }

    /**
     * @Route("/afficher/{id}", name="afficher")
     */
    public function afficherSortie($id, Request $request): Response
    {
        if ($this->isGranted('ROLE_USER')) {

            $sortie = $this->sortieRepo->find($id);

            /*$participants = $sortie->getParticipants();*/

            return $this->render('sortie/afficher.html.twig', [
                'sortie' => $sortie,
                /*'participants' => $participants,*/
            ]);
        }
        return $this->redirectToRoute('app_logout');
    }

    /**
     * @Route("/afficherPart", name="participants")
     */
    public function afficherParticipants(Request $request): JsonResponse
    {
        if ($this->isGranted('ROLE_USER')) {
            $sortie = $this->sortieRepo->find($request->request->get('sortie_id'));
            $participants = $sortie->getParticipants();
            $i = 0;
            if (sizeof($participants) > 0) {
                foreach ($participants as $participant) {
                    $json_data[$i++] = array('id' => $participant->getId(), 'pseudo' => $participant->getPseudo(),
                        'nom' => $participant->getNom(),
                        'prenom' => $participant->getPrenom());
                }
            } else {
                $json_data[$i++] = array('pseudo' => '', 'nom' => 'Pas de participants inscrit à cette sortie.',
                    'prenom' => '');
            }
            return new JsonResponse($json_data);
        }
    }

    /**
     * @Route("/inscription/{id}", name="inscription")
     */
    public function inscriptionSortie($id, Request $request, EntityManagerInterface $em): Response
    {
        if ($this->isGranted('ROLE_USER')) {

            $participant = new Participant();
            $idP = $this->getUser();
            $participant = $this->participantRepo->find($idP);
            $sortie = $this->sortieRepo->find($id);
            $participant->addInscription($sortie);

            $em->flush();
            $this->addFlash('success', 'Vous avez été inscrit à la sortie ' . $sortie->getNom());
            return $this->redirectToRoute('app_main');
        }
        return $this->redirectToRoute('app_logout');
    }

    /**
     * @Route("/desistement/{id}", name="desistement")
     */
    public function desisitementSortie($id, Request $request, EntityManagerInterface $em): Response
    {
        if ($this->isGranted('ROLE_USER')) {

            $participant = new Participant();
            $idP = $this->getUser();
            $participant = $this->participantRepo->find($idP);
            $sortie = $this->sortieRepo->find($id);
            $participant->removeInscription($sortie);

            $em->flush();
            $this->addFlash('success', "Vous n'êtes plus inscrit à la sortie " . $sortie->getNom());
            return $this->redirectToRoute('app_main');
        }
        return $this->redirectToRoute('app_logout');
    }


}
