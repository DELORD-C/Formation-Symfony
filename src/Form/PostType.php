<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class PostType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // Liste des types de champs : https://symfony.com/doc/current/reference/forms/types.html
        $builder
            ->add('subject', TextType::class)
            ->add('body', TextareaType::class)
            ->add('submit', SubmitType::class);
    }
}