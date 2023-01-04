<?php

namespace App\Form;

use App\Entity\Alternance;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AlternanceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('numAlternance', IntegerType::class,
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
            'data_class' => Alternance::class,
        ]);
    }
}
