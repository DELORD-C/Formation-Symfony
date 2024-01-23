<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;

class CommentType extends AbstractType {
    public function buildForm (FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('body', TextareaType::class, [
                'attr' => [
                    'placeholder' => 'Title'
                ],
                'row_attr' => [
                    'class' => 'form-floating mb-3'
                ]
            ])
            ->add('submit', SubmitType::class)
        ;
    }
}