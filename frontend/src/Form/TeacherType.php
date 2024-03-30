<?php

namespace App\Form;

use App\Entity\Level;
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
            ->add('levels', EntityType::class, [
                'class' => Level::class,
                'choice_label' => 'level_name',
                'multiple' => true,
                'expanded' => false, // Ensure it renders as a select input
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
