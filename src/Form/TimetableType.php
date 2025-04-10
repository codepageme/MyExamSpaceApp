<?php

namespace App\Form;

use App\Entity\Classroom;
use App\Entity\Exam;
use App\Entity\Examtype;
use App\Entity\Subject;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class TimetableType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Date', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('startTime', TimeType::class, [
                'widget' => 'single_text',
            ])
            ->add('endTime', TimeType::class, [
                'widget' => 'single_text',
            ])
            ->add('totalQuestions')
            ->add('totalMarks')
            ->add('Duration', TextType::class, [
                'label' => 'Duration (H:i:s)',
            ])
            ->add('theoryQuestions')
            ->add('theoryDuration', TextType::class, [
                'label' => 'Theory Duration (H:i:s)',
            ])
            ->add('Subject', EntityType::class, [
                'class' => Subject::class,
                'choice_label' => 'course',
            ])
            ->add('Classroom', EntityType::class, [
                'class' => Classroom::class,
                'choice_label' => 'classname',
            ])
            ->add('Examtype', EntityType::class, [
                'class' => Examtype::class,
                'choice_label' => 'name',
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Save',
            ])
        ;

        $builder
            ->get('Duration')
            ->addModelTransformer(new DurationTransformer())
        ;

        $builder
            ->get('theoryDuration')
            ->addModelTransformer(new TheoryDurationTransformer())
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Exam::class,
        ]);
    }
}

class DurationTransformer implements DataTransformerInterface
{
    public function transform(mixed $value): mixed
    {
        return $value ? $value->format('H:i:s') : '';
    }

    public function reverseTransform(mixed $value): mixed
    {
        if (!$value) {
            return null;
        }

        list($hours, $minutes, $seconds) = explode(':', $value);
        $dt = new \DateTime();
        $dt->setTime($hours, $minutes, $seconds);

        return $dt;
    }
}

class TheoryDurationTransformer implements DataTransformerInterface
{
    public function transform(mixed $value): mixed
    {
        return $value ? $value->format('H:i:s') : '';
    }

    public function reverseTransform(mixed $value): mixed
    {
        if (!$value) {
            return null;
        }

        list($hours, $minutes, $seconds) = explode(':', $value);
        $dt = new \DateTime();
        $dt->setTime($hours, $minutes, $seconds);

        return $dt;
    }
}