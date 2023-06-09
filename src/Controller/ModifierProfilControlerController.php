<?php

namespace App\Controller;

use App\Entity\ChangePassword;
use App\Entity\Participant;
use App\Entity\Site;
use App\Form\ChangerMdpType;
use App\Form\FormImageType;
use App\Form\ModifParticipantType;
use App\Repository\ParticipantRepository;

use App\Repository\SiteRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\String\Slugger\SluggerInterface;
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
    public function index(SluggerInterface $slugger,EntityManagerInterface $em,Request $request): Response
    {
        if ($this->isGranted('ROLE_USER')) {

            $user = new Participant();
            // $user->setNom($user);
            $user = $this->getUser();
            $formModif = $this->createForm(ModifParticipantType::class, $user);
            $formModif->handleRequest($request);
            $hash = $user->getPassword();


            if($formModif->isSubmitted() && $formModif->isValid()) {

                if (password_verify($formModif->get('password')->getData(), $hash)) {

                    $user->setPassword($hash);

                    $photo = $formModif->get('photo')->getData();

                    if ($photo) {
                        $originalFilename = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);

                        // this is needed to safely include the file name as part of the URL$safeFilename = $slugger->slug($originalFilename);
                        $safeFilename = $slugger->slug($originalFilename);
                        $newFilename = $safeFilename.'-'.uniqid().'.'.$photo->guessExtension();

                        // Move the file to the directory where brochures are storedtry {
                        $photo->move(
                            $this->getParameter('images_directory'),
                            $newFilename
                        );
                        $user->setPhoto($newFilename);
                    }
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
        return $this->redirectToRoute('app_logout');
    }

    /**
     * @Route("/mdp", name="app_mdp")
     */
    public function change_user_password(Request $request,EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher) {

        if ($this->isGranted('ROLE_USER')) {

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
                    $this->addFlash('reussie','Votre mot de passe a bien été modifié');

                    return $this->redirectToRoute('app_profil');
                }else{
                    $this->addFlash('erreur','Les mots de passe ne correspondent pas');
                    return $this->redirectToRoute('app_mdp');
                }

            }

            return $this->render("mon_profil/mdp.html.twig",
                ['formMdp' => $formMdp->createView()]);
        }
        return $this->redirectToRoute('app_logout');
    }



    /**
     * @return string
     */
    private function generateUniqueFileName()
    {
        // md5() reduces the similarity of the file names generated by
        // uniqid(), which is based on timestamps
        return md5(uniqid());
    }
}
