<?php

namespace App\Form;

use App\Data\SearchData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class SearchForm extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
       $builder

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


    public function configueOption(OptionsResolver $resolver){

        $resolver->setDefault([

            'data_class' => SearchData::class,
            'method'=>'GET',
            'csrf_protection'=>false


        ]);


    }
    public function getBlockPrefix()
    {
        return '';
    }

}