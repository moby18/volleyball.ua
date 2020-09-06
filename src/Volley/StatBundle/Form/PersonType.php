<?php

namespace Volley\StatBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Volley\StatBundle\Entity\Person;

class PersonType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('sex', ChoiceType::class, [
                'choices' => Person::SEX(),
                'expanded' => true,
                'multiple' => false,
                'label' => false
            ])
            ->add('firstName')
            ->add('middleName')
            ->add('lastName')
            ->add('slug')
            ->add('country', null, [
                'label' => 'Country of citizenship'
            ])
            ->add('birthDate', DateType::class, array(
                    'years' => range(1950, 2016),
                    'format' => 'dd-MMM-yyyy'
                )
            )
            ->add('nationality', null, [
                'label' => 'Country of birth'
            ])
            ->add('file', null, [
                'label' => 'Photo'
            ])
            ->add('type', ChoiceType::class, [
                'choices' => Person::TYPE()
            ])
            ->add('title')
            ->add('position', ChoiceType::class, [
                'choices' => array_merge([' - ' => null], Person::POSITIONS()),

            ])
            ->add('grade')
            ->add('height')
            ->add('weight')
            ->add('spike')
            ->add('block')
            ->add('description', TextareaType::class, [
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
            'data_class' => 'Volley\StatBundle\Entity\Person'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'volley_bundle_statbundle_person';
    }
}
