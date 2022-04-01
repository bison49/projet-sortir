<?php

namespace App\Form;

use App\Data\SearchData;
use Symfony\Component\Form\AbstractType;
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
       $builder ->add('q',TextType::class,[
           'label'=>false,
        'required'=>false,
        'attr' => ['placeholder' => 'Rechercher']

       ])

           ->add('sortie1', CheckboxType::class, [
               'label' => 'Sortie dont je suis lorganisateur',
               'required' => false,
               'mapped'=>false,
           ])
           ->add('sortie2', CheckboxType::class, [
               'label' => 'Sortie auxquelles je suis inscrit',
               'required' => false,
               'mapped'=>false,
           ])
           ->add('sortie3', CheckboxType::class, [
               'label' => 'Sortie auxquelles je ne suis pas inscrit',
               'required' => false,
               'mapped'=>false,
           ])
           ->add('sortie4', CheckboxType::class, [
               'label' => 'Sortie passées',
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