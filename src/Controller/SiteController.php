<?php

namespace App\Controller;

use App\Entity\Site;
use App\Form\AddSiteType;
use App\Repository\SiteRepository;

use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/site", name="app_sites_")
 */
class SiteController extends AbstractController
{
    private $siteRepo;

    function __construct(SiteRepository $siteRepo)
    {
        $this->siteRepo = $siteRepo;
    }
    /**
     * @Route("/ajout", name="ajout")
     */
    public function isPublished(Request $request, SiteRepository $siteRepository):Response {




        $site = new Site();
        $formAdd = $this->createForm(AddSiteType::class, $site);
        $formAdd ->handleRequest($request);

        if($formAdd->isSubmitted() && $formAdd->isValid()) {
            try {
                $this->siteRepo->add($site);
            } catch (OptimisticLockException $e) {
            } catch (ORMException $e) {
            }
            $this->addFlash('succes', 'Le site('.$site->getNom().') a été ajoutée');

            return $this->redirectToRoute("app_sites_ajout");
        }

        if(!empty($request->request->get('siteRech'))){
            $mot = $request->request->get('siteRech');
            $nom = $this->siteRepo->findOneBySomeField($mot);

        }else{
            $nom = $this->siteRepo->findAll();        }


        return $this->render("site/index.html.twig",
            ['nom'=>$nom, "formAdd" => $formAdd->createView()]);
    }
    /**
     * @Route("/supprimerSite/{id}", name="supprimer_site")
     */
    public function supprimerSite($id,Request $request): Response
    {

        $site = $this->siteRepo->find($id);
        try {
            $this->siteRepo->remove($site);
        } catch (OptimisticLockException $e) {
        } catch (ORMException $e) {
        }

        return $this->redirectToRoute('app_sites_ajout');
    }



}
