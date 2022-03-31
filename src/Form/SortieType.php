<?php

namespace App\Form;

use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Participant;
use App\Entity\Site;
use App\Entity\Sortie;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use function Sodium\add;

class SortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, ["label" => "Nom de la sortie :"])
            ->add('dateHeureDebut', DateTimeType::class, ["label" => "Date et heure de la sortie :", "widget" => 'single_text'])
            ->add('dateFinInscription', DateType::class, ["label" => "Date limite d'inscription :", "widget" => 'single_text'])
            ->add('duree', IntegerType::class, ["label" => "Durée :"])
            ->add('nbInscriptionMax', IntegerType::class, ["label" => "Nombre de places :"])
            ->add('description', TextareaType::class, ["label" => "Description et infos :", "required" => "false"])
            ->add('ville', EntityType::class, ['class' => 'App\Entity\Ville',
                'mapped' => false,
                'choice_label' => 'nom',
                'placeholder' => 'Selectionner une ville',
                'required' => false]);
        $builder->get('ville')->addEventListener(
            FormEvents::POST_SUBMIT, function (FormEvent $event) {
            $form = $event->getForm();
            $this->addLieuField($form->getParent(), $form->getData());
        });
        $builder->addEventListener(FormEvents::POST_SET_DATA,
            function (FormEvent $event) {
                $data = $event->getData();
                /* @var $lieu \App\Entity\Lieu */
                $lieu = $data->getNoLieu();
                $form = $event->getForm();
                if ($lieu) {
                    $ville = $lieu->getVille();
                    $this->addLieuField($form, $ville);
                    $form->get('ville')->setData($ville);
                } else {
                    $this->addLieuField($form, null);
                }
            });

    }

    private function addLieuField(FormInterface $form, ?Ville $ville)
    {
        $builder = $form->add('noLieu', EntityType::class, ['class' => Lieu::class, 'choice_label' => 'nom',
            'placeholder' => $ville ? 'Selectionnez votre lieu' : 'Selectionnez votre ville',
            'required' => true, 'auto_initialize' => false, 'choices' => $ville ? $ville->getNoLieux() : []]);
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
