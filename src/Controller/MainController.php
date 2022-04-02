<?php

namespace App\Controller;

use App\Entity\Site;
use App\Form\RechercherParSaisieTexteForm;
use App\Form\RechercherParSaisieTexteType;
use App\Form\SearchForm;
use App\Repository\SiteRepository;
use App\Repository\SortieRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Console\Input\Input;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Data\SearchData;
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
     */
    public function index(SortieRepository $repository, Request $request,PaginatorInterface $paginator): Response
    {

        if ($this->isGranted('ROLE_USER')) {


            $form = $this->createForm(RechercherParSaisieTexteForm::class);
            $form->handleRequest($request);

            $listeSorties = $this->sortieRepo->findByPublish(1);

            $sorties = $paginator->paginate(
                $listeSorties, // Requête contenant les données à paginer (ici nos articles)
                $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
                8 // Nombre de résultats par page
            );

            return $this->renderForm('main/index.html.twig', compact("sorties", "form"));
        }

        $this->addFlash('inactif',"Votre compte est actuellement bloquée, veuillez contacter l'administrateur.");
        return $this->redirect('/');
    }


}