<?php

namespace App\Controller;

use App\Entity\Site;
use App\Form\SearchForm;
use App\Repository\SiteRepository;
use App\Repository\SortieRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function index(SortieRepository $repository, Request $request): Response
    {

        if ($this->isGranted('ROLE_USER')) {

            $data = new SearchData();
            $data->page = $request->get('page', 1);
            $form2 = $this->createForm(SearchForm::class, $data);
            $form2->handleRequest($request);
            $sorties2 = $repository->findSearch2($data);


            $form = $this->createFormBuilder()
                ->add('Site', EntityType::class, [
                    'class' => Site::class,
                    'choice_label' => 'nom',
                    'placeholder' => 'Choissez un Site',
                    'query_builder' => function (SiteRepository $siteRepository) {
                        return $siteRepository->createQueryBuilder('site')->orderBy('site.nom', 'ASC');
                    }
                ])->getForm();

            $sorties = $this->sortieRepo->findByPublish(1);

            return $this->renderForm('main/index.html.twig', compact("sorties", "form2", "sorties2", "form"));
        }

        $this->addFlash('inactif',"Votre compte est actuellement bloquée, veuillez contacter l'administrateur.");
        return $this->redirect('/');
    }


}