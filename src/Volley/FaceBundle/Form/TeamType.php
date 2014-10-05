<?php

namespace Volley\FaceBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints as Assert;

class TeamType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('rating')
            ->add('rank')
            ->add('place')
//            ->add('players',null,
//                array(
//                    'multiple' => true,
//                    'constraints' => array(
//                        new Assert\Count(
//                            array(
//                                'min' => 2,
//                                'max' => 2,
//                                'exactMessage' => 'Team should contain exactly 2 players.',
//                            )
//                        )
//                    )
//                )
//            )
            ->add('playerOne',null,
                array(
                    'multiple' => false
                )
            )
            ->add('playerTwo',null,
                array(
                    'multiple' => false
                )
            )
            ->add('tournament')
            ->add('file')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Volley\FaceBundle\Entity\Team'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'volley_bundle_facebundle_team';
    }
}
