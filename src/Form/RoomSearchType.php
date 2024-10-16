<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RoomSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'required' => false,
                'label' => 'Nom de la salle',
            ])
            ->add('capacity', IntegerType::class, [
                'required' => false,
                'label' => 'Capacité minimale',
            ])
            ->add('equipments', ChoiceType::class, [
                'choices' => [
                    'Projecteur' => 'projector',
                    'Tableau blanc' => 'whiteboard',
                    'Ordinateur' => 'computer',
                    // Ajoutez d'autres équipements selon vos besoins
                ],
                'multiple' => true,
                'expanded' => true,
                'required' => false,
                'label' => 'Équipements',
            ])
            ->add('ergonomics', ChoiceType::class, [
                'choices' => [
                    'Luminosité naturelle' => 'natural_light',
                    'Accessibilité PMR' => 'wheelchair_accessible',
                    'Climatisation' => 'air_conditioning',
                    // Ajoutez d'autres critères ergonomiques
                ],
                'multiple' => true,
                'expanded' => true,
                'required' => false,
                'label' => 'Critères ergonomiques',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}