<?php

namespace Volley\StatBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Volley\StatBundle\Entity\Tournament;

class TournamentType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('sex', ChoiceType::class, [
                'choices' => [
                    null  => 'No',
                    'Man' => Tournament::MAN,
                    'Woman' => Tournament::WOMAN,
                ]
            ])
            ->add('status', null, [
                'label' => false,
                'attr' => ['data-toggle' => 'toggle', 'data-on' => "Enabled", 'data-off' => "Disabled", 'data-onstyle' => 'info']
            ])
            ->add('country')
            ->add('title')
            ->add('h1')
            ->add('keywords')
            ->add('description');
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Volley\StatBundle\Entity\Tournament'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'volley_statbundle_tournament';
    }
}
