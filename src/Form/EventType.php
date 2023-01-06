<?php

namespace App\Form;

use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre de l\'événement'
            ])
            ->add('startTime', DateTimeType::class, [
                'label' => 'Début de l\'événement'
            ])
            ->add('endTime', DateTimeType::class, [
                'label' => 'Fin de l\'événement'
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description de l\'événement',
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
