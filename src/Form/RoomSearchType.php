<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RoomSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'required' => false,
            ])
            ->add('minCapacity', IntegerType::class, [
                'required' => false,
            ])
            ->add('equipment', ChoiceType::class, [
                'choices' => [
                    'Projector' => 'projector',
                    'Whiteboard' => 'whiteboard',
                    'Computer' => 'computer',
                    'Video conferencing' => 'video_conferencing',
                    'Touch screen' => 'touch_screen',
                ],
                'multiple' => true,
                'expanded' => true,
                'required' => false,
            ])
            ->add('ergonomics', ChoiceType::class, [
                'choices' => [
                    'Natural light' => 'natural_light',
                    'Wheelchair accessible' => 'wheelchair_accessible',
                    'Air conditioning' => 'air_conditioning',
                    'Soundproof' => 'soundproof',
                    'Ergonomic furniture' => 'ergonomic_furniture',
                ],
                'multiple' => true,
                'expanded' => true,
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'method' => 'GET',
            'csrf_protection' => false,
        ]);
    }
}