<?php

namespace Volley\StatBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TeamSeasonType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('team')
            ->add('season')
            ->add('team_season_persons', 'collection', array('type' => new TeamSeasonPersonType(), 'allow_add' => true, 'allow_delete' => true, 'by_reference' => false, 'label' => false))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Volley\StatBundle\Entity\TeamSeason'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'volley_statbundle_team_season';
    }
}
