<?php

namespace Volley\StatBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Volley\StatBundle\Entity\TeamRepository;

class RoundType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($builder) {
            $form = $event->getForm();
            $data = $event->getData();
            $form
                ->add('name')
                ->add('ordering')
                ->add('type')
                ->add('season')
                ->add('teams', EntityType::class, [
                    'class' => 'Volley\StatBundle\Entity\Team',
                    'required' => false,
                    'expanded' => false,
                    'multiple' => true,
                    'query_builder' => function (TeamRepository $repository) use ($data) {
                        $query = $repository->createQueryBuilder('t')
                            ->add('orderBy', 't.id ASC');
                        if ($data->getSeason()) {
                            $query
                                ->leftJoin('t.seasons', 'season')
                                ->andWhere('season = ?1')
                                ->setParameter(1, $data->getSeason()->getId());
                        }
                        return $query;
                    }
                ]);
        });
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Volley\StatBundle\Entity\Round'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'volley_statbundle_round';
    }
}
