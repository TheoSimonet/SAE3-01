<?php

namespace App\Form;

use App\Entity\ProjetTER;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjetTERType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('numpProj', IntegerType::class,
                ['attr' => ['class' => 'projet__form__input']])
            ->add('titre', TextareaType::class, [
                'empty_data' => '',
                'attr' => ['class' => 'projet__form__input projet__text__area']
            ])
            ->add('description', TextareaType::class, [
                'empty_data' => '',
                'attr' => ['class' => 'projet__form__input projet__text__area']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProjetTER::class,
        ]);
    }
}
