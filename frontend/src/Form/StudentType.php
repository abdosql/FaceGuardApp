<?php

namespace App\Form;

use App\Entity\Branch;
use App\Entity\FacialRecognitionLog;
use App\Entity\Group;
use App\Entity\RFIDCard;
use App\Entity\Student;
use App\Entity\Teacher;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class StudentType extends AbstractType
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
            //->add('rfidCard', EntityType::class, [
            //    'class' => RFIDCard::class,
            //    'choice_label' => 'id',
            //])
            //->add('facialRecognition', EntityType::class, [
            //    'class' => FacialRecognitionLog::class,
            //    'choice_label' => 'id',
            //])
            ->add('branch', EntityType::class, [
                'class' => Branch::class,
                'choice_label' => 'branch_name',
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
            'data_class' => Student::class,
        ]);
    }
}
