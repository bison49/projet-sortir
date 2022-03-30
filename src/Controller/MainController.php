<?php

namespace App\Controller;

use App\Repository\SortieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
     */
    public function index(): Response
    {
        $sorties= $this->sortieRepo->findAll();

        return $this->render('main/index.html.twig',compact("sorties"));
    }

}
