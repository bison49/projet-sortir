<?php

namespace App\Controller;

use App\Form\RechercherParSaisieTexteForm;
use App\Repository\SortieRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{

    private $sortieRepo;


    function __construct(SortieRepository $sortieRepo)  //injection de dépendances
    {
        $this->sortieRepo = $sortieRepo;
    }


    /**
     * @Route("/main", name="app_main")
     * @throws \Exception
     */
    public function index(SortieRepository $repository, Request $request, PaginatorInterface $paginator): Response
    {

        if ($this->isGranted('ROLE_USER')) {

            $form = $this->createForm(RechercherParSaisieTexteForm::class);
            $form->handleRequest($request);


            $site = $form["site"]->getData();
            $recherche = $form["rechercher"]->getData();
            $orga = $form["orga"]->getData();
            $inscrit = $form["inscrit"]->getData();
            $pasInscrit = $form["pasInscrit"]->getData();
            $passee = $form["passee"]->getData();

            $recherche_date_1=$form["recherche_date_recherche1"]->getData();
            $recherche_date_2=$form["recherche_date_recherche2"]->getData();
            if($recherche_date_2 != null){
                $recherche_date_2->add(new \DateInterval(('P1D')));
            }

            $id = $this->getUser()->getId();

            $listeSorties = $this->sortieRepo->rechercheFiltrer($site, $recherche, $orga, $id, $inscrit, $pasInscrit, $passee,$recherche_date_1,$recherche_date_2);


            $sorties = $paginator->paginate(
                $listeSorties, // Requête contenant les données à paginer (ici nos articles)
                $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
                8 // Nombre de résultats par page
            );

            return $this->renderForm('main/index.html.twig', compact("sorties", "form"));
        }
        if ($this->isGranted('ROLE_NON')) {
            $this->addFlash('inactif', "Votre compte est actuellement bloquée, veuillez contacter l'administrateur.");
        }
        return $this->redirect('/');
    }


}