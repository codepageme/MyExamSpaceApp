<?php

namespace App\Form;

use App\Entity\Classroom;
use App\Entity\Department;
use App\Entity\Question;

use App\Entity\Subject;
use App\Entity\teacher;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuestionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('content')
            ->add('class')
            ->add('subject')
            ->add('QuestionType')
            ->add('questiontype', EntityType::class, [
                'class' => QuestionType::class,
'choice_label' => 'id',
            ])
            ->add('teacher', EntityType::class, [
                'class' => teacher::class,
'choice_label' => 'id',
            ])
            ->add('Subject', EntityType::class, [
                'class' => Subject::class,
'choice_label' => 'id',
            ])
            ->add('Classroom', EntityType::class, [
                'class' => Classroom::class,
'choice_label' => 'id',
            ])
            ->add('Department', EntityType::class, [
                'class' => Department::class,
'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Question::class,
        ]);
    }
}
