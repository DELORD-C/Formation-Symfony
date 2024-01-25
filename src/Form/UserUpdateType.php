<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserUpdateType extends AbstractType {
    public function buildForm (FormBuilderInterface $builder, array $options): void
    {
        $builder->add('email', EmailType::class, [
            'attr' => [
                'placeholder' => 'Email'
            ],
            'row_attr' => [
                'class' => 'form-floating mb-3'
            ]
        ]);

        if ($options['currentUser'] !== $options['updatedUser']) {
            $choices = ['Admin' => 'ROLE_ADMIN'];
            if (in_array('ROLE_SUPER_ADMIN', $options['currentUser']->getRoles())) {
                $choices['Super Admin'] = 'ROLE_SUPER_ADMIN';
            }
            $builder->add('roles', ChoiceType::class, [
                'multiple' => true,
                'required' => false,
                'choices' => $choices
            ]);
        }

        $builder->add('submit', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('data_class', User::class);
        $resolver->setDefault('currentUser', null);
        $resolver->setDefault('updatedUser', null);
    }
}