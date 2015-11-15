<?php

namespace Volley\StatBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class GameType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('number')
            ->add('season')
            ->add('tour')
            ->add('homeTeam')
            ->add('awayTeam')
//            ->add('name')
            ->add('duration')
            ->add('homeTeamEmpty')
            ->add('awayTeamEmpty')
            ->add('score','hidden',[])
            ->add('scoreSetHome')
            ->add('scoreSetAway')
            ->add('played')
            ->add('date')
            ->add('sets', 'collection', array('type' => new GameSetType(), 'allow_add' => true, 'allow_delete' => true, 'by_reference' => false, 'label' => false))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Volley\StatBundle\Entity\Game'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'volley_statbundle_game';
    }
}
