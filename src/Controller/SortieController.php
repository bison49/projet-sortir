<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Entity\Sortie;
use App\Form\LieuxType;
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

            $formAjoutLieux = $this->createForm(LieuxType::class);

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
                    $this->addFlash('success', 'Votre sortie a été publiée');
                }
                return $this->redirectToRoute('app_main');
            }
            return $this->render('sortie/index.html.twig', [
                'sortieForm' => $sortieForm->createView(), "LieuxType" => $formAjoutLieux->createView()
            ]);
        }
        return $this->redirectToRoute('app_logout');
    }

    /**
     * @Route("/afficher/{id}", name="afficher")
     */
    public function afficherSortie($id, Request $request, SortieRepository $repository): Response
    {
        if ($this->isGranted('ROLE_USER')) {

            $sortie = $this->sortieRepo->find($id);


            return $this->render('sortie/afficher.html.twig', [
                'sortie' => $sortie,
            ]);
        }
        return $this->redirectToRoute('app_logout');
    }

    /**
     * @Route("/afficherPart", name="participants")
     */
    public function afficherParticipants(Request $request)
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
        return $this->redirectToRoute('app_logout');
    }

    /**
     * @Route("/inscription/{id}", name="inscription")
     */
    public function inscriptionSortie($id, EntityManagerInterface $em): Response
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
    public function desisitementSortie($id, EntityManagerInterface $em): Response
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

    /**
     * @Route("/annuler/{id}", name="annuler")
     */
    public function annulerSortie($id, Request $request, EntityManagerInterface $em): Response
    {
        if ($this->isGranted('ROLE_USER')) {

            $sortie = $this->sortieRepo->find($id);
            if ($_POST) {
                $sortie->setEtat($this->etatRepo->find(6));
                $sortie->setDescription($request->request->get('motif'));
                $participants = $sortie->getParticipants();
                //Effacer les particpants inscrits à la sortie
                foreach ($participants as $participant) {
                    $sortie->removeParticipant($participant);
                }
                $em->flush();
                $this->addFlash('success', 'Votre sortie(' . $sortie->getNom() . ') a bien été annulée');
                return $this->redirectToRoute('app_main');
            }

            return $this->render('/sortie/annuler.html.twig', [
                'sortie' => $sortie,
            ]);
        }
        return $this->redirectToRoute('app_logout');
    }

    /**
     * @Route("/modifier/{id}", name="modifier")
     */
    public function modifierSortie($id, Request $request, EntityManagerInterface $em): Response
    {
        if ($this->isGranted('ROLE_USER')) {

            $sortie = $this->sortieRepo->find($id);
            $sortieForm = $this->createForm(SortieType::class, $sortie);
            $sortieForm->handleRequest($request);

            if ($sortieForm->isSubmitted() && $sortieForm->isValid()) {

                if ($request->request->get('enr')) {
                    $id = 1;
                } else {
                    $id = 2;
                }
                $sortie->setEtat($this->etatRepo->find($id));
                $sortie->setSiteOrganisateur($this->getUser()->getNoSite());
                $sortie->setOrganisateur($this->getUser());

                $em->flush();
                if ($id == 1) {
                    $this->addFlash('success', 'Votre sortie a été modifiée et enregistrée');
                } else {
                    $this->addFlash('success', 'Votre sortie a été modifiée et publiée');
                }
                return $this->redirectToRoute('app_main');
            }
            return $this->render('/sortie/modifier.html.twig', [
                'sortie' => $sortie,
                'sortieForm' => $sortieForm->createView()
            ]);
        }
        return $this->redirectToRoute('app_logout');
    }

    /**
     * @Route("/supprimer/{id}", name="supprimer")
     */
    public function supprimerSortie($id): Response
    {
        if ($this->isGranted('ROLE_USER')) {
            $sortie = $this->sortieRepo->find($id);
            try {
                $this->sortieRepo->remove($sortie);
            } catch (OptimisticLockException $e) {
            } catch (ORMException $e) {
            }

            $this->addFlash('erased', 'Votre sortie a été supprimée');

            return $this->redirectToRoute('app_main');
        }
        return $this->redirectToRoute('app_logout');
    }

}
