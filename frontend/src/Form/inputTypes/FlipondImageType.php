<?php

namespace App\Form\inputTypes;

use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FlipondImageType extends FileType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefault('compound', false);
        $resolver->setDefault('multiple', false);
        $resolver->setDefault('data_class', null);
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        // Pass any additional variables to the view if needed
        $view->vars['foo'] = 'bar';

        // Customize the HTML template for the input field
        $view->vars['attr']['class'] = 'filepond filepond-input-circle'; // Add custom CSS class
    }
}