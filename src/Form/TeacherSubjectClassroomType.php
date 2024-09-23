<?php
namespace App\Form;

use App\Entity\TeacherSubjectClassroom;
use App\Entity\Teacher;
use App\Entity\Subject;
use App\Entity\Classroom;
use App\Entity\Department;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TeacherSubjectClassroomType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('teacher', EntityType::class, [
                'class' => Teacher::class,
                'choice_label' => 'Username', // Adjust as needed
            ])
            ->add('subject', EntityType::class, [
                'class' => Subject::class,
                'choice_label' => 'Course',
            ])
            ->add('classroom', EntityType::class, [
                'class' => Classroom::class,
                'choice_label' => 'classname',
            ])
            ->add('department', EntityType::class, [
                'class' => Department::class,
                'choice_label' => 'department',
                'required' => false, // Make department optional
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TeacherSubjectClassroom::class,
        ]);
    }
}
