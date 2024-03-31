<?php

namespace App\Form;

use App\Entity\Branch;
use App\Entity\Group;
use App\Entity\Student;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StudentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username')
            ->add('roles')
            ->add('password')
            ->add('first_name')
            ->add('last_name')
            ->add('phone_number')
            ->add('profile_image')
            ->add('gender')
            ->add('email')
            ->add('rfidCard', EntityType::class, [
                'class' => Student::class,
                'choice_label' => 'id',
            ])
            ->add('student', EntityType::class, [
                'class' => Student::class,
                'choice_label' => 'id',
            ])
            ->add('facialRecognition', EntityType::class, [
                'class' => Student::class,
                'choice_label' => 'id',
            ])
            ->add('group_', EntityType::class, [
                'class' => Group::class,
                'choice_label' => 'id',
            ])
            ->add('branch', EntityType::class, [
                'class' => Branch::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Student::class,
        ]);
    }
}
