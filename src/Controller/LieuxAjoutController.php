<?php

namespace App\Controller;

use App\Entity\Lieu;
use App\Entity\Ville;
use App\Form\LieuxType;


use App\Repository\LieuRepository;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LieuxAjoutController extends AbstractController
{
    private $lieuRepo;
    private $villeRepo;

    function __construct(LieuRepository $lieuRepo, VilleRepository $villeRepo)
    {
        $this->lieuRepo = $lieuRepo;
        $this->villeRepo = $villeRepo;

    }

    /**
     * @Route("/lieux/ajout", name="app_lieux_ajout")
     */
    public function index(Request $request, EntityManagerInterface $em)
    {

        if ($this->isGranted('ROLE_USER')) {
            $lieu = new Lieu();


            if ($_POST) {
                $ville = $request->request->get('ville');
                $nom = $request->request->get('nom');
                $rue = $request->request->get('rue');
                $longitude = $request->request->get('longitude');
                $latitude = $request->request->get('latitude');


                $lieu->setNoVille($this->villeRepo->find($ville));
                $lieu->setNom($nom);
                $lieu->setRue($rue);
                $lieu->setLatitude($latitude);
                $lieu->setLongitude($longitude);

                try {
                    $this->lieuRepo->add($lieu);
                } catch (OptimisticLockException $e) {
                } catch (ORMException $e) {
                }
                $lieux = $this->lieuRepo->findBy(["noVille" => $lieu->getNoVille()->getId()]);
                $this->addFlash('success', 'Un lieu a été ajouté');

                $i = 0;
                if (sizeof($lieux) > 0) {
                    foreach ($lieux as $item) {
                        $json_data[$i++] = array('id'=>$item->getId(),'nom' => $item->getNom());
                    }
                    return new JsonResponse($json_data);
                } else {
                    $json_data[$i++] = array('id' => '', 'nom' => 'Pas de lieu correspondant à votre recherche.'
                        );
                    return new JsonResponse($json_data);
                }

            }
        }
        return $this->redirectToRoute('app_logout');
    }
}
