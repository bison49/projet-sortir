<?php

namespace App\Controller;

use App\Entity\Ville;
use App\Form\VillesType;
use App\Repository\VilleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VillesController extends AbstractController
{
    private $villeRepo;

    function __construct(VilleRepository $villeRepo)
    {
        $this->villeRepo = $villeRepo;
    }

    /**
     * @Route("/villes", name="app_villes")
     */
    public function index(Request $request): Response
    {

        $ville = new Ville();
        $villeForm = $this->createForm(VillesType::class, $ville);
        $villeForm->handleRequest($request);

        if($request->request->get('villeRech') != null){
            $mot = $request->request->get('villeRech');
            $villes = array($this->villeRepo->findOneBySomeField($mot));
            dd($villes);
        }else{
            $villes = $this->villeRepo->findAll();
        }

        if ($villeForm->isSubmitted() && $villeForm->isValid()) {

            $this->villeRepo->add($ville);
            $this->addFlash('succes', 'La ville a été ajoutée');

            return $this->redirectToRoute('app_villes');
        }


        return $this->render('villes/index.html.twig', [
            'villes' => $villes,
            'villeForm' => $villeForm->createView(),
        ]);
    }

}
