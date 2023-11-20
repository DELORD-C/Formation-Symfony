<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class ReviewType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('movie', TextType::class)
            ->add('body', TextareaType::class)
            ->add('rating', RangeType::class, [
                'attr' => [
                    'min' => 0,
                    'max' => 10
                ],
            ])
            ->add('submit', SubmitType::class)
        ;
    }
}