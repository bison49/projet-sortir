<?php

namespace App\Controller;

use App\Entity\ChangePassword;
use App\Entity\Participant;
use App\Entity\Site;
use App\Form\ChangerMdpType;
use App\Form\ModifParticipantType;
use App\Repository\ParticipantRepository;

use App\Repository\SiteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use function PHPUnit\Framework\equalTo;

class ModifierProfilControlerController extends AbstractController
{

    private $partiRepo;
    private $siteRepo;

    function __construct(ParticipantRepository $partiRepo, SiteRepository $siteRepo)
    {
        $this->partiRepo = $partiRepo;
        $this->siteRepo = $siteRepo;

    }

    /**
     * @Route("/profil", name="app_profil")
     */
    public function index(ParticipantRepository $participantRepository,EntityManagerInterface $em,Request $request): Response
    {
        $user = new Participant();
       // $user->setNom($user);
        $user = $this->getUser();
        $formModif = $this->createForm(ModifParticipantType::class, $user);
        $formModif->handleRequest($request);
        $hash = $user->getPassword();


        if($formModif->isSubmitted() && $formModif->isValid()) {

            if (password_verify($formModif->get('Password')->getData(), $hash)) {

                $user->setPassword($hash);
                $em->flush();
                $this->addFlash('success','Votre profil a bien été modifié');


            }else{
                $this->addFlash('erreur','Votre mot de passe est incorrect');
            }

            return $this->redirectToRoute("app_profil");
        }


        return $this->render("mon_profil/index.html.twig",
            ['formModif' => $formModif->createView()]);
    }

    /**
     * @Route("/mdp", name="app_mdp")
     */

    public function change_user_password(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher) {

        $user = new ChangePassword();

        $formMdp = $this->createForm(ChangerMdpType::class, $user);
        $formMdp->handleRequest($request);

        if($formMdp->isSubmitted() && $formMdp->isValid()) {

            if ($formMdp->get('newPassword')->getData()==($formMdp->get('newConfirm')->getData())) {
               $participant = new Participant();
               $id = $this ->getUser();
               $participant = $this ->partiRepo->find($id);

               $participant ->setPassword(


                $userPasswordHasher->hashPassword(
                        $participant,
                        $formMdp->get('newPassword')->getData()

                    )
                );

                $entityManager->flush();

            }

        }

        return $this->render("mon_profil/mdp.html.twig",
            ['formMdp' => $formMdp->createView()]);
    }



}
