<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $roles = [
            'ROLE_SUBSCRIBER' => 'ROLE_SUBSCRIBER',
            'ROLE_WRITER' => 'ROLE_WRITER',
            'ROLE_ADMIN' => 'ROLE_ADMIN'
        ];

        if ($options['data'] && !in_array('ROLE_SUPERADMIN', $options['data']->getRoles())) {
            $roles['ROLE_SUPERADMIN'] = 'ROLE_SUPERADMIN';
        }

        $builder
            ->add('email')
            ->add('roles', ChoiceType::class, [
                'choices' => $roles,
                'multiple' => true,
                'required' => false
            ])
            ->add('save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'user' => null
        ]);
    }
}