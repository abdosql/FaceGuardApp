<?php

namespace App\Form;

use App\Entity\Admin;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class AdminType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('first_name')
            ->add('last_name')
            ->add('phone_number')
            ->add('gender', ChoiceType::class,[
                'choices' => [
                    'Male' => 'male',
                    'Female' => 'female',
                ],
            ])
            ->add('email')
            ->add('roles', ChoiceType::class, [
                'label' => 'Roles',
                'choices' => [
                    'Full Access' => 'ROLE_ADMIN',
                    'Manage Students' => 'ROLE_MANAGE_STUDENTS',
                    'Manage Teachers' => 'ROLE_MANAGE_TEACHERS',
                    'Manage Courses' => 'ROLE_MANAGE_COURSES',
                    'Manage Classes and Groups' => 'ROLE_MANAGE_CLASSES',
                    'Manage System Settings' => 'ROLE_MANAGE_SYSTEM_SETTINGS',

                ],
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('imageFile', VichImageType::class, [
                'label' => 'Image',
                'required' => false, // Set to true if the image is required
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Admin::class,
        ]);
    }
}
