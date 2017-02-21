<?php

namespace Volley\StatBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Tetranz\Select2EntityBundle\Form\Type\Select2EntityType;

class RosterPersonType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('number')
            ->add('person',  Select2EntityType::class, [
                'label' => false,
                'multiple' => false,
                'remote_route' => 'stat_person_json',
                'class' => 'Volley\StatBundle\Entity\Person',
                'primary_key' => 'id',
                'minimum_input_length' => 2,
                'page_limit' => 10,
                'allow_clear' => true,
                'delay' => 250,
                'cache' => true,
                'cache_timeout' => 60000, // if 'cache' is true
                'language' => 'uk',
                'placeholder' => "Введіть ім'я гравця, тренера або працівника команди",
                'attr' => ['width'=>'100%']
            ]);
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Volley\StatBundle\Entity\RosterPerson'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'volley_statbundle_rosterperson';
    }
}
