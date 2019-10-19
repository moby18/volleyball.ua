<?php

namespace Volley\StatBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Volley\StatBundle\Entity\Team;

class TeamType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
	        ->add('sex', ChoiceType::class, [
		        'choices' => Team::SEX(),
		        'expanded' => true,
		        'multiple' => false,
		        'label' => false
	        ])
            ->add('name')
            ->add('slug')
            ->add('shortName')
            ->add('city')
            ->add('country')
//            ->add('seasons')
            ->add('image', FileType::class, array(
                    'data_class' => null,
                    'required' => false
                )
            )
            ->add('logoImage', FileType::class, array(
                    'data_class' => null,
                    'required' => false
                )
            )
            ->add('hallImage', FileType::class, array(
                    'data_class' => null,
                    'required' => false
                )
            )
            ->add('address')
            ->add('lat')
            ->add('lng')
            ->add('phone')
            ->add('fax')
            ->add('email')
            ->add('site')
            ->add('hall')
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
            'data_class' => 'Volley\StatBundle\Entity\Team'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'volley_statbundle_team';
    }
}
