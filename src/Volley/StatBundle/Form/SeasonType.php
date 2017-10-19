<?php

namespace Volley\StatBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Volley\StatBundle\Entity\Season;

class SeasonType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('fromYear')
            ->add('toYear')
            ->add('status', null, [
                'label' => false,
                'attr' => ['data-toggle' => 'toggle', 'data-on' => "Enabled", 'data-off' => "Disabled", 'data-onstyle' => 'info']
            ])
            ->add('standingSystem',ChoiceType::class, [
                'choices'  => array(
                    'Points' => Season::STANDING_SYSTEM_POINTS,
                    'Wins' => Season::STANDING_SYSTEM_WINS
                ),
            ])
            ->add('tournamentTable', null, [
                'label' => false,
                'attr' => ['data-toggle' => 'toggle', 'data-on' => "Show Tables", 'data-off' => "Hide Tables", 'data-onstyle' => 'info']
            ])
            ->add('tournament')
            ->add('teams');
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Volley\StatBundle\Entity\Season'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'volley_statbundle_season';
    }
}
