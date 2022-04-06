<?php

namespace App\Command;

/*use App\Entity\Participant;*/

use App\Entity\Participant;
use App\Entity\Site;
use App\Repository\ParticipantRepository;
use App\Repository\SiteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Serializer\Encoder\CsvEncoder;

use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;

/*
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\YamlEncoder;
*/
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class CreateUserFromCsvOrXmlOrYamlFileCommand extends Command
{
    private SymfonyStyle $io;
    private EntityManagerInterface $entityManager;
    private string $dataDirectory;
    private ParticipantRepository $participantRepository;
    private $encoder;

    public function __construct( EntityManagerInterface $entityManager,string $dataDirectory,ParticipantRepository $participantRepository,SiteRepository $SiteRepository)
    {

        parent::__construct();
        $this->dataDirectory=$dataDirectory;
        $this->entityManager=$entityManager;
        $this->participantRepository=$participantRepository;
        $this->SiteRepository=$SiteRepository;

    }

    protected static $defaultName = 'app:create-users-from-file';

    protected static $defaultDescription = 'Add a short description for your command';

    protected function configure(): void
    {
        $this
            ->setDescription('importer des données d\'un fichier csv')

        ;
    }
    protected function initialize(InputInterface $input, OutputInterface $output):void
    {
        $this->io=new SymfonyStyle($input,$output);
        parent::initialize($input, $output);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->createUsers();

        return Command::SUCCESS;
    }

    private function getDataFromFile():array
    {
       $file= $this->dataDirectory .'utilisateurs.csv';
        $fileExtension=pathinfo($file,PATHINFO_EXTENSION);

        $normalizers=[new ObjectNormalizer()];

        $encoders=[ new CsvEncoder()/* ,new XmlEncoder(),new YamlEncoder()*/];

        $serializers= new Serializer($normalizers,$encoders);
        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));



        /** @var string $fileString */

        $fileString=file_get_contents($file);

        $data=$serializers->decode($fileString,$fileExtension);

        if (array_key_exists('results',$data)){
            return $data['results'];
        }
/*        dd($data);*/
        return $data;
    }
    private function createUsers():void
    {

        $this->io->section('CREATION DES UTILISATEUR A PARTIR DU FICHIER');
    // variable $usersCreated a titre informatif si le user est deja creer afin d'indiquer le nombre de user creaer reellement

        $usersCreated=0;
        foreach ($this->getDataFromFile()as $row){
/*                            dd($user);*/
            if(array_key_exists('mail',$row) && !empty($row['mail'])){
                $user = $this->participantRepository->findOneBy(['mail'=>$row['mail']]);

                if (!$user){
                    $user=new Participant();

                    $site=new Site();
                    /*                dd($user);*/
                    $user
                        ->setNoSite($site ->getId())
                        ->setPseudo($row['pseudo'])
                        ->setRoles(array($row['roles']))
                        ->setPassword($row['password'])
                        ->setActif($row['actif'])
                        ->setNom($row['nom'])
                        ->setPrenom($row['prenom'])
                        ->setTelephone($row['telephone'])
                        ->setMail($row['mail'])
                        ;
                }
/*                dd($user);*/
                $this->entityManager->persist($user);
                $usersCreated++;
            }
        }

/*    $this->getDataFromFile();*/

        $this->entityManager->flush();

            if ($usersCreated>1){
                $string= " {$usersCreated} UTILISATEURS CREER EN BASE DE DONNEES.";
            }elseif($usersCreated===1)
            {
                $string= "1 UTILISATEUR A ETE CREER EN BASE DE DONNEES.";
            }
            else{
                $string="AUCUN UTILISATEUR N'A ETE CREER EN BASE DE DONNEES.";
            }

            $this->io->success($string);
    }


}
