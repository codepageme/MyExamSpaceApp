<?php

namespace App\Form;

use App\Entity\Teacher;
use App\Entity\Subject;
use App\Entity\Classroom; 
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AssignTeacherType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('teacher', EntityType::class, [
                'class' => Teacher::class,
                'choice_label' => 'username',
                'label' => 'Select Teacher'
            ])
            ->add('subject', EntityType::class, [
                'class' => Subject::class,
                'choice_label' => 'Course',
                'label' => 'Assign Subject'
            ])
            ->add('class', EntityType::class, [
                'class' => Classroom::class, // Change to your actual class entity name
                'choice_label' => 'Classname',
                'label' => 'Assign Class'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
