<?php

namespace App\Form;

use App\Entity\Branch;
use App\Entity\Teacher;
use App\Form\inputTypes\FlipondImageType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class TeacherType extends AbstractType
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

            ->add('branches', EntityType::class, [
                'class' => Branch::class,
                'choice_label' => 'branch_name',
                'multiple' => true,
                'expanded' => true,
                'by_reference' => false,
            ])
            ->add('imageFile', VichImageType::class, [
                'label' => 'Image',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Teacher::class,
        ]);
    }
}
