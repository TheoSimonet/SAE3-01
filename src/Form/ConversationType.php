<?php

namespace App\Form;

use App\Entity\Conversation;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConversationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('subject', TextType::class, [
                'empty_data' => 'Test',
                'required' => true
            ])
            ->add('participant', EntityType::class,
                [
                    'required' => true,
                    'class' => User::class,
                    'multiple' => true,
                    'expanded' => true,
                    'choice_label' => function (User $user) {
                        return $user->getFirstName() . ' ' . $user->getLastname();
                    },
                    'query_builder' => function (UserRepository $userRepository) {
                        return $userRepository->createQueryBuilder('c')->orderBy('c.firstname', 'ASC');
                    }
                ]);
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Conversation::class,
        ]);
    }
}
