<?php

namespace App\Form;

use App\Entity\Conversation;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConversationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('subject', TextType::class, ['empty_data' => 'Test'])
            ->add('locked', CheckboxType::class)
            ->add('createdAt', DateTimeType::class, [
                'input' => 'datetime_immutable'])
            ->add('author', EntityType::class,
                [
                    'class' => User::class,
                    'choice_label' => 'lastname',
                ]
            )
            ->add('participant', CollectionType::class,
                [
                    'entry_type' => User::class,
                ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Conversation::class,
        ]);
    }
}
