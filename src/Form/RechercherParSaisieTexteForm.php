<?php

namespace App\Form;

use App\Entity\Site;
use App\Repository\SiteRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RechercherParSaisieTexteForm extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder



            ->add('Site', EntityType::class, [
                'class' => Site::class,
                'choice_label' => 'nom',
                'placeholder' => 'Choissez un Site',
                'query_builder' => function (SiteRepository $siteRepository) {
                    return $siteRepository->createQueryBuilder('site')->orderBy('site.nom', 'ASC');
                }
            ])


            ->add('rechercher',TextType::class,[

                'required'=>false,
                'label'=>'Rechercher une sortie  commençant par:',
                'attr' => [
                    'onkeyup'=>'fonctionRechercheTextFiltre()' ,  "type"=>"search",'placeholder'=>'Rechercher','aria-label'=>'Search'],


            ])





            ->add('sortie1', CheckboxType::class, [
                'label' => 'Sortie dont je suis lorganisateur',
                'attr' => ['class'=>"table-filter" , 'onchange'=>'rechercherAvancer()', 'data-table'=>'#Table'],
                'required' => false,
                'mapped'=>false,
            ])



            ->add('sortie2', CheckboxType::class, [
                'label' => 'Sortie auxquelles je suis inscrit',
                'attr' => [ 'class'=>"table-filter" , 'onchange'=>'rechercherAvancer2()', 'data-table'=>'#Table'],
                'required' => false,
                'mapped'=>false,
            ])
            ->add('sortie3', CheckboxType::class, [
                'label' => 'Sortie auxquelles je ne suis pas inscrit',
                'attr' => ['class'=>"table-filter" , 'onchange'=>'rechercherAvancer4()', 'data-table'=>'#Table'],
                'required' => false,
                'mapped'=>false,
            ])
            ->add('sortie4', CheckboxType::class, [
                'label' => 'Sortie passées',
                'attr' => ['class'=>"table-filter" , 'onchange'=>'rechercherAvancer3()', 'data-table'=>'#Table'],
                'required' => false,
                'mapped'=>false,
            ])

        ;
    }






    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
