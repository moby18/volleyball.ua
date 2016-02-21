<?php

namespace Volley\StatBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
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
                'choices' => Person::SEX,
                'expanded' => true,
                'multiple' => false,
                'label' => false
            ])
            ->add('firstName')
            ->add('middleName')
            ->add('lastName')
            ->add('country')
            ->add('birthDate', 'date', array(
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
                'choices' => Person::TYPE
            ])
            ->add('title')
            ->add('position', ChoiceType::class, [
                'choices' => Person::POSITIONS
            ])
            ->add('grade')
            ->add('height')
            ->add('weight')
            ->add('spike')
            ->add('block')
            ->add('description');
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
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