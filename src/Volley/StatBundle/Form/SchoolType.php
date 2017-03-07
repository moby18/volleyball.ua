<?php

namespace Volley\StatBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SchoolType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('slug')
            ->add('shortName')
            ->add('city')
            ->add('address')
            ->add('lat')
            ->add('lng')
            ->add('phone')
            ->add('fax')
            ->add('email')
            ->add('site')
            ->add('hall')
            ->add('country')
            ->add('image', 'file', array(
                    'data_class' => null,
                    'required' => false
                )
            )
            ->add('logoImage', 'file', array(
                    'data_class' => null,
                    'required' => false
                )
            )
            ->add('hallImage', 'file', array(
                    'data_class' => null,
                    'required' => false
                )
            )
            ->add('description', 'textarea', [
                'attr' => array(
                    'class' => 'tinymce',
                    'data-theme' => 'advanced' // simple, advanced, bbcode
                ),
                'required' => false
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Volley\StatBundle\Entity\School'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'volley_statbundle_school';
    }
}