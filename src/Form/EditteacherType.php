<?php

namespace App\Form;

use App\Entity\Teacher;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class EditteacherType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('prefix', ChoiceType::class, [
            'choices'  => [
                'Mr.' => 'Mr.',
                'Mrs.' => 'Mrs.',
                'Ms.' => 'Ms.',
                'Dr.' => 'Dr.',
                'Prof.' => 'Prof.',
            ],
            'label' => 'Prefix',
        ])
            ->add('firstname', TextType::class, [
                'label' => 'First Name',
                'required' => true,
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Last Name',
                'required' => true,
            ])
            ->add('username', TextType::class, [
                'label' => 'Username',
                'required' => true,
            ])
            ->add('email', TextType::class, [
                'label' => 'Email',
                'required' => true,
            ])
            ->add('number', TextType::class, [
                'label' => 'Phone Number',
                'required' => true,
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Password',
                'required' => false, // Make password not mandatory, so it only updates if changed
                'empty_data' => '', // Ensure password remains unchanged if left empty
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Teacher::class,
        ]);
    }
}
