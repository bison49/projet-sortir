<?php

namespace App\Controller;

use App\Entity\Ville;
use App\Form\VillesType;
use App\Repository\VilleRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/villes", name="app_villes_")
 */

class VillesController extends AbstractController
{
    private $villeRepo;

    function __construct(VilleRepository $villeRepo)
    {
        $this->villeRepo = $villeRepo;
    }

    /**
     * @Route("/ajout", name="ajout")
     */
    public function index(Request $request): Response
    {

        $ville = new Ville();
        $villeForm = $this->createForm(VillesType::class, $ville);
        $villeForm->handleRequest($request);

        if(!empty($request->request->get('villeRech')) ){
            $mot = $request->request->get('villeRech');
            $villes = $this->villeRepo->findOneByKeyword($mot);
        }else{
            $villes = $this->villeRepo->findAll();
        }

        if ($villeForm->isSubmitted() && $villeForm->isValid()) {

            try {
                $this->villeRepo->add($ville);
            } catch (OptimisticLockException $e) {
            } catch (ORMException $e) {
            }
            $this->addFlash('succes', 'La ville('.$ville->getNom().') a été ajoutée');

            return $this->redirectToRoute('app_villes_ajout');
        }


        return $this->render('villes/index.html.twig', [
            'villes' => $villes,
            'villeForm' => $villeForm->createView(),
        ]);
    }

    /**
     * @Route("/supprimerVille/{id}", name="supprimer_villes")
     */
    public function supprimerVille($id,Request $request): Response
    {

        $ville = $this->villeRepo->find($id);
        try {
            $this->villeRepo->remove($ville);
        } catch (OptimisticLockException $e) {
        } catch (ORMException $e) {
        }

        return $this->redirectToRoute('app_villes_ajout');
    }

}
