<?php

namespace App\Form;

use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Participant;
use App\Entity\Site;
use App\Entity\Sortie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateIntervalType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Date;
use function Sodium\add;

class SortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom',TextType::class,["label"=>"Nom de la sortie :"])
            ->add('dateHeureDebut',DateTimeType::class,["label"=>"Date et heure de la sortie :","widget"=>'single_text'])
            ->add('dateFinInscription',DateType::class,["label"=>"Date limite d'inscription :","widget"=>'single_text'])
            ->add('duree',NumberType::class,["label"=>"Durée :"])
            ->add('nbInscriptionMax',NumberType::class,["label"=>"Nombre de places :"])
            ->add('description',TextareaType::class,["label"=>"Description et infos :","required"=>"false"])
            ->add('siteOrganisateur',EntityType::class,["label"=>'Ville organisatrice :',
                'class'=>Site::class,
                'choice_label'=>'nom',
                'choice_value'=>'id'
            ])
            ->add('noLieu',EntityType::class,["label"=>'lieu :',
                'class'=>Lieu::class,
                'choice_label'=>'nom'])
        ;

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
