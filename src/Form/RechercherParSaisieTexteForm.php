<?php

namespace App\Form;

use App\Entity\Site;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RechercherParSaisieTexteForm extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('site', EntityType::class, [
                'class' => Site::class,
                'choice_label' => 'nom',
                'placeholder' => 'Choisissez un Site',
                'required'=>false
            ])
            ->add('rechercher',TextType::class,[
                'required'=>false,
                'label'=>'Rechercher une sortie  commençant par:',
            ])
            ->add('orga', CheckboxType::class, [
                'label' => "Sortie dont je suis l'organisateur/trice",
                'required' => false,
            ])
            ->add('inscrit', CheckboxType::class, [
                'label' => 'Sortie auxquelles je suis inscrit',
                'required' => false,
            ])
            ->add('pasInscrit', CheckboxType::class, [
                'label' => 'Sortie auxquelles je ne suis pas inscrit',
                'required' => false,
            ])
            ->add('passee', CheckboxType::class, [
                'label' => 'Sortie passées',
                'required' => false,
            ])
            ->add('Rechercher', SubmitType::class,[
                'attr' =>['class' => 'btn btn-primary w-100 mt-3']])

            ->add('passee', CheckboxType::class, [
                'label' => 'Sortie passées',
                'required' => false,
            ])

            ->add('recherche_date_recherche1', DateType::class, [ 'required' => false,"widget" => 'single_text'])
            ->add('recherche_date_recherche2', DateType::class, ['required' => false, "widget" => 'single_text'])


        ;

    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
