<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Participant;
use App\Entity\Site;
use App\Entity\Sortie;
use App\Entity\Ville;

use Faker;
use Symfony\Component\Security\Core\User\User;

class AjoutFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');

        //créer 10 Villes
        for($i = 0 ; $i < 10 ; $i++ ){
            $ville = new Ville();
            $ville->setNom($faker->city());
            $ville->setCodePostal($faker->numberBetween(01000, 95999));
            $manager->persist($ville);
        }
        $manager->flush();

        //créer 30 Lieux
        for($i = 0 ; $i < 30 ; $i++){
            $lieu = new Lieu();
            $lieu->setNom($faker->company());
            $lieu->setLatitude($faker->latitude());
            $lieu->setLongitude($faker->longitude());
            $lieu->setRue($faker->streetAddress());
            $villes = $manager->getRepository(Ville::class)->findAll();
            shuffle($villes);
            $lieu->setNoVille($villes[0]);
            $manager->persist($lieu);
        }
        $manager->flush();
        //créer les états
        $creee = new Etat();
        $creee->setLibelle('Créée');
        $manager->persist($creee);
        $manager->flush();
        $ouverte = new Etat();
        $ouverte->setLibelle('Ouverte');
        $manager->persist($ouverte);
        $manager->flush();
        $cloturee = new Etat();
        $cloturee->setLibelle('Cloturée');
        $manager->persist($cloturee);
        $manager->flush();
        $en_cours = new Etat();
        $en_cours->setLibelle('Activité en cours');
        $manager->persist($en_cours);
        $manager->flush();
        $passee = new Etat();
        $passee->setLibelle('Passée');
        $manager->persist($passee);
        $manager->flush();
        $annulee = new Etat();
        $annulee->setLibelle('Annulée');
        $manager->persist($annulee);
        $manager->flush();

        //Créer les sites
        $chartres = new Site();
        $chartres->setNom("Chartres de Bretagne");
        $manager->persist($chartres);
        $manager->flush();
        $nantes = new Site();
        $nantes->setNom("Nantes");
        $manager->persist($nantes);
        $manager->flush();
        $niort = new Site();
        $niort->setNom("Niort");
        $manager->persist($niort);
        $manager->flush();
        $angers = new Site();
        $angers->setNom("Angers");
        $manager->persist($angers);
        $manager->flush();
        $Quimperle = new Site();
        $Quimperle->setNom("Quimperle");
        $manager->persist($Quimperle);
        $manager->flush();
        $Paris = new Site();
        $Paris->setNom("Paris");
        $manager->persist($Paris);
        $manager->flush();
        $St_Etienne = new Site();
        $St_Etienne->setNom("St Etienne");
        $manager->persist($St_Etienne);
        $manager->flush();

        //Créer des utilisateurs tests
        $thierry = new Participant();
        $thierry->setNom('LARGEAU');
        $thierry->setPrenom('Thierry');
        $sites = $manager->getRepository(Site::class)->findAll();
        shuffle($sites);
        $thierry->setNoSite($sites[0]);

        $roles = $manager->getRepository(Site::class)->findAll();
        shuffle($roles);
        $thierry->setRoles(array('ROLE_ADMIN'));

        $thierry->setPseudo('Thierry');
        $thierry->setActif(1);
        $thierry->setMail($faker->email);
        $thierry->setTelephone($faker->e164PhoneNumber);
      //  $thierry->setUsername('thielarg');
        $thierry->setPassword('$2y$13$o17MdEOR13KUBUbgFkM/4ePNwE92I65cPAzS.6qTtIWWHJ5fC7H3S');
        $manager->persist($thierry);
        $manager->flush();



        $karim = new Participant();
        $karim->setNom('EL AZHAR');
        $karim->setPrenom('Karim');

        $sites = $manager->getRepository(Site::class)->findAll();
        shuffle($sites);
        $karim->setNoSite($sites[0]);

        $roles = $manager->getRepository(Site::class)->findAll();
        shuffle($roles);
        $karim->setRoles(array('ROLE_ADMIN'));



        $karim->setPseudo('kelazhar');
        $karim->setActif(1);
        $karim->setMail($faker->email());
        $karim->setTelephone($faker->e164PhoneNumber);
        //  $thierry->setUsername('kelazhar');
        $karim->setPassword('$2y$13$NqsHtde38hbNE9PbFKNNVuzq2hdz/vghf6GdTlpT8tAS5pXytXZvy');
        $manager->persist($karim);
        $manager->flush();

        for($i = 0 ; $i < 8 ; $i++) {
            $sherlock = new Participant();
            $sherlock->setNom($faker->name);
            $sherlock->setPrenom($faker->lastName);
            $sherlock->setPseudo($faker->userName);

            $sites = $manager->getRepository(Site::class)->findAll();
            shuffle($sites);
            $sherlock->setNoSite($sites[0]);

            $roles = $manager->getRepository(Site::class)->findAll();
            shuffle($roles);
            $sherlock->setRoles(array('ROLE_USER'));

            $sherlock->setActif(1);
            $sherlock->setMail($faker->email());
            $sherlock->setTelephone($faker->e164PhoneNumber);
            //   $sherlock->setUsername('sherholm');
            $sherlock->setPassword('$2y$13$AdoSa78xWr0Eq2hHi7rEMeEtdyPfrN2IsbZ4fdezOmLBLaB4r3FGC');
            $manager->persist($sherlock);
            $manager->flush();

        }

        for($i = 0 ; $i < 50 ; $i++){
            $sortie = new Sortie();
            $sortie->setNom($faker->text(50));
            $sortie->setDateHeureDebut($faker->dateTimeBetween('-6 months', '+ 6 months'));

            $sortie->setDateFinInscription($faker->dateTimeInInterval($sortie->getDateHeureDebut(), '-5 days'));

            $sortie->setDuree($faker->numberBetween(1,10));
            $sortie->setNbInscriptionMax($faker->numberBetween(4, 20));
            $sortie->setDescription($faker->text(240));

                    //etat au hasard
            $etats = $manager->getRepository(Etat::class)->findAll();
            shuffle($etats);
            $sortie->setEtat($etats[0]);

            //tire un organisateur au hasard
            $participants = $manager->getRepository(Participant::class)->findAll();
            shuffle($participants);
            $sortie->setOrganisateur($participants[0]);



            //lieu au hasard
            $lieux = $manager->getRepository(Lieu::class)->findAll();
            shuffle($lieux);
            $sortie->setNoLieu($lieux[0]);

            //site est celui de l'organisateur
            $sortie->setSiteOrganisateur($participants[0]->getNoSite());

         /*   //publié au sort
            $sortie->setIsPublished($faker->numberBetween(0,1));*/
            $manager->persist($sortie);
        }
        $manager->flush();


    }


}
