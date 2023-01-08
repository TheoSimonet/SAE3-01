<?php

namespace App\Form;

use App\Entity\ProjetTER;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjetTERType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextareaType::class, [
                'empty_data' => '',
                'attr' => ['class' => 'projet__form__input projet__text__area'],
            ])
            ->add('description', TextareaType::class, [
                'empty_data' => '',
                'attr' => ['class' => 'projet__form__input projet__text__area'],
            ])
            ->add('libProjet', ChoiceType::class,
                [
                    'choices' => [
                        'Master 1' => 'Master 1',
                        'Master 2' => 'Master 2',
                    ], ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProjetTER::class,
        ]);
    }
}
