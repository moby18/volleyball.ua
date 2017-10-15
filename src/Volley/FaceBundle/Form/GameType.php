<?php

namespace Volley\FaceBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GameType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('number')
            ->add('tournament')
            ->add('homeTeam')
            ->add('homeTeamEmpty')
            ->add('awayTeam')
            ->add('awayTeamEmpty')
            ->add('score')
//            ->add('scoreSet')
            ->add('scoreSetHome')
            ->add('scoreSetAway')
            ->add('played')
            ->add('played')
            ->add('date')
            ->add('round')
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Volley\FaceBundle\Entity\Game'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'volley_bundle_facebundle_game';
    }
}
