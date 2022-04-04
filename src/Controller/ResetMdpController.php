<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Form\ResetType;
use App\Repository\ParticipantRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
//use Symfony\Component\Security\Core\User\User;
//use Symfony\Component\Security\Core\User;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;


class ResetMdpController extends AbstractController
{
    private $partiRepo;

    function __construct(ParticipantRepository $partiRepo)
    {
        $this->partiRepo = $partiRepo;
    }
    /**
     * @Route("/motdepasseoublie", name="app_reset")
     */
    public function index(Request $request, MailerInterface $mailer, TokenGeneratorInterface $tokenGenerator, EntityManagerInterface $entityManager): Response
        {

            $user = new Participant();
            $formMdp = $this->createForm(ResetType::class);
            $formMdp->handleRequest($request);

            if ($formMdp->isSubmitted() && $formMdp->isValid()) {

                $email = $formMdp->getData('email');
                $em = $this->getDoctrine()->getManager();
                $etablissement = $em->getRepository(Participant::class)
                    ->findOneBy([
                        'mail' => $email
                    ]);
                if (!$etablissement) {
                    $this->addFlash('warning', "Cet email n'existe pas.");
                    return $this->redirectToRoute("app_reset");
                }
                else {
                    $this->addFlash('cool', "Mail trouvé");

                    $token = $tokenGenerator->generateToken();
                    $url = $this->generateUrl('app_reset_password', array('token' => $token), UrlGeneratorInterface::ABSOLUTE_URL);
                    $etablissement->setResetToken($token);
                    $entityManager ->flush();


                    $mail = (new Email())
                        ->from(new Address('expediteur@demo.test', 'Mon nom'))
                        ->to('destinataire@demo.test')
                        ->subject('edz')
                        ->html($url);

                    $mailer->send($mail);


                    //return $this->redirectToRoute("app_reset");
                    return $this->render('reset_mdp/index.html.twig',
                        ['formMdp' => $formMdp->createView(), compact($etablissement)]);

                }
            }



        return $this->render('reset_mdp/index.html.twig',
            ['formMdp' => $formMdp->createView()]);

    }

    /** Réinisialiation du mot de passe par mail
     * @Route("/reset/{token}", name="app_reset_password")
     */
    public function resetPassword(Request $request, string $token, UserPasswordEncoderInterface $passwordEncoder)
    {

        $etab = $request->query->get('etab');
       // dd($etab);

        //Reset avec le mail envoyé
        if ($request->isMethod('POST')) {
            $entityManager = $this->getDoctrine()->getManager();

            $user = $entityManager->getRepository(Participant::class)->findOneBy(['resetToken'=>$token]);


            if ($user === null) {
                $this->addFlash('danger', 'Mot de passe non reconnu');
                return $this->redirectToRoute('app_main');
            }


            $user->setResetToken(null);
            $user->setPassword($passwordEncoder->encodePassword($user, $request->request->get('password')));
            $entityManager->flush();

            $this->addFlash('notice', 'Mot de passe mis à jour !');

            return $this->redirectToRoute('app_main');                    }
        else {

            return $this->render('reset_mdp/reset.html.twig');

            //return $this->redirectToRoute('app_main');
        }


    }}
