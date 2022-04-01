<?php

namespace App\Controller;

use App\Repository\ParticipantRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    private $partiRepo;

    function __construct(ParticipantRepository $partiRepo)
    {
        $this->partiRepo = $partiRepo;
    }


    /**
     * @Route("/admin", name="app_admin")
     */
    public function index(): Response
    {



        $nom = $this->partiRepo->findAll();



        return $this->render('admin/index.html.twig', [
            'nom'=>$nom,
        ]);




    }

    /**
     * @Route("/admin/{id}", name="app_admin_desact")
     */
    public function desactiver($id, EntityManagerInterface $entityManager): Response
    {
        $user = $this->partiRepo->find($id);
        $user->setActif(false);
        $user->setRoles(["ROLE_NON"]);
        $entityManager->flush();

        return $this->redirectToRoute('app_admin',
        );
    }

    /**
     * @Route("/admina/{id}", name="app_admin_activ")
     */
    public function activer($id, EntityManagerInterface $entityManager): Response
    {
        $user = $this->partiRepo->find($id);
        $user->setActif(true);

        $entityManager->flush();

        return $this->redirectToRoute('app_admin',
        );
    }
}
