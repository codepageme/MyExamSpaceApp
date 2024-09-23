<?php

namespace App\Form;

use App\Entity\Classroom;
use App\Entity\Student;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class CreateStudentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Firstname', TextType::class, [
                'label' => 'First Name',
                'attr' => ['class' => 'form-control'] // Optional: For Bootstrap styling
            ])
            ->add('Lastname', TextType::class, [
                'label' => 'Last Name',
                'attr' => ['class' => 'form-control']
            ])
            ->add('Middlename', TextType::class, [
                'label' => 'Middle Name',
                'required' => false, // Assuming middle name is optional
                'attr' => ['class' => 'form-control']
            ])
            ->add('Age', IntegerType::class, [
                'label' => 'Age',
                'attr' => ['class' => 'form-control']
            ])
            ->add('Gender', ChoiceType::class, [
                'label' => 'Gender',
                'choices' => [
                    'Male' => 'male',
                    'Female' => 'female',
                ],
                'expanded' => true, // Renders as radio buttons
                'attr' => ['class' => 'form-control']
            ])
            ->add('classroom', EntityType::class, [
                'class' => Classroom::class,
                'choice_label' => 'classname', // Assuming the Classroom entity has a 'name' field
                'label' => 'Classroom',
                'attr' => ['class' => 'form-control']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Student::class,
        ]);
    }
}
