<?php

namespace App\Controller;

use App\Entity\Site;
use App\Form\AddSiteType;
use App\Repository\SiteRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SiteController extends AbstractController
{
    private $siteRepo;

    function __construct(SiteRepository $siteRepo)
    {
        $this->siteRepo = $siteRepo;
    }
    /**
     * @Route("/site", name="app_sites")
     */

    public function isPublished(Request $request, SiteRepository $siteRepository):Response {




        $site = new Site();
        $formAdd = $this->createForm(AddSiteType::class, $site);
        $formAdd ->handleRequest($request);

        if($formAdd->isSubmitted() && $formAdd->isValid()) {
            $this->siteRepo->add($site);
            $this->addFlash('succes', 'La ville('.$site->getNom().') a été ajoutée');

            return $this->redirectToRoute("app_sites");
        }

        if(!empty($request->request->get('siteRech'))){
            $mot = $request->request->get('siteRech');
            $nom = $this->siteRepo->findOneBySomeField($mot);

        }else{
            $nom = $this->siteRepo->findAll();        }


        return $this->render("site/index.html.twig",
            ['nom'=>$nom, "formAdd" => $formAdd->createView()]);
    }



}
