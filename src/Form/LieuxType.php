<?php

namespace App\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LieuxType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('ville', EntityType::class, ['class' => 'App\Entity\Ville',
                'mapped' => false,
                'choice_label' => 'nom',
                'placeholder' => 'Selectionner une ville',
                'required'=>true
                ])
            ->add('nom', TextType::class, ["label" => "Lieux :",
                'required'=>true])
            ->add('rue', TextType::class, ["label" => "Rue :",
                'required'=>true])
            ->add('latitude', NumberType::class, ["label" => "Latitude :"])
            ->add('longitude', NumberType::class, ["label" => "Longitude :"])


        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
