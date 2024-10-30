<?php

namespace App\Form;

use App\Entity\TodoList; // Ensure you're using the correct entity
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class TodoItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('task', TextType::class, [
                'constraints' => [
                    new NotBlank(), // Ensure this field is not empty
                ],
            ])
            ->add('deadline', DateTimeType::class, [
                'widget' => 'single_text', // Use single_text widget for HTML5
                'html5' => true,           // Enable HTML5
                'input' => 'datetime',     // Input format
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TodoList::class, // Make sure this points to your entity
        ]);
    }
}
