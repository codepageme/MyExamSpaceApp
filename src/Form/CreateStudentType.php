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
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class CreateStudentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Firstname', TextType::class, [
                'label' => 'First Name',
                'attr' => ['class' => 'form-control']
            ])
            ->add('Lastname', TextType::class, [
                'label' => 'Last Name',
                'attr' => ['class' => 'form-control']
            ])
            ->add('Middlename', TextType::class, [
                'label' => 'Middle Name',
                'required' => false,
                'attr' => ['class' => 'form-control']
            ])
            ->add('dateOfBirth', DateType::class, [
                'label' => 'Date of Birth',
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control']
            ])
            ->add('Gender', ChoiceType::class, [
                'label' => 'Gender',
                'choices' => [
                    'Male' => 'male',
                    'Female' => 'female',
                ],
                'expanded' => true,
                'attr' => ['class' => 'form-control']
            ])
            ->add('classroom', EntityType::class, [
                'class' => Classroom::class,
                'choice_label' => function (Classroom $classroom) {
                    return $classroom->getClassname() . 
                           ($classroom->getDepartment() ? ' - ' . $classroom->getDepartment()->getDepartment() : '');
                },
                'label' => 'Classroom',
                'attr' => ['class' => 'form-control']
            ])
            ->add('admissionYear', IntegerType::class, [
                'label' => 'Year of Admission',
                'attr' => [
                    'class' => 'form-control',
                    'min' => 1900,
                    'max' => date('Y'),
                    'placeholder' => 'e.g., 2021'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Student::class,
        ]);
    }
}
