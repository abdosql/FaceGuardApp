<?php

namespace App\Form;

use App\Entity\Branch;
use App\Entity\Course;
use App\Entity\Teacher;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CourseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('course_name')
            ->add("course_duration")
            ->add('teacher', EntityType::class, [
                'class' => Teacher::class,
                'choice_label' => 'getDisplayName',
                'by_reference' => false,
            ])
            ->add('branches', EntityType::class, [
                'class' => Branch::class,
                'choice_label' => 'branch_name',
                'multiple' => true,
                'expanded' => true,
                'by_reference' => false,

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Course::class,
        ]);
    }
}
