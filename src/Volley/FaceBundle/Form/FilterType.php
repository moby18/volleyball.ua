<?php

namespace Volley\FaceBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilterType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('category',EntityType::class, [
                'class' => 'Volley\FaceBundle\Entity\Category',
                'required' => false
            ])
            ->add('state', ChoiceType::class, [
                'choices' => ['All' => 12, 'Unpublished' => 0, 'Published' => 1],
                'label' => 'Filter',
                'required' => false,
                'multiple' => false,
                'expanded' => false,
                'placeholder' => false
            ])
            ->add('featured', ChoiceType::class, [
                'choices' => ['All' => 12, 'None Featured' => 0 , 'Featured' => 1],
                'label' => 'Filter',
                'required' => false,
                'multiple' => false,
                'expanded' => false,
                'placeholder' => false
            ])
            ->add('recommended', ChoiceType::class, [
                'choices' => ['All' => 12, 'None Recommended' => 0, 'Recommended' => 1],
                'label' => 'Filter',
                'required' => false,
                'multiple' => false,
                'expanded' => false,
                'placeholder' => false
            ])
            ->add('vu', ChoiceType::class, [
                'choices' => ['All' => 12, 'None VU' => 0, 'VU' => 1],
                'label' => 'Filter',
                'required' => false,
                'multiple' => false,
                'expanded' => false,
                'placeholder' => false
            ])
            ->add('user',EntityType::class, [
                'class' => 'Volley\UserBundle\Entity\User',
                'required' => false
            ])
            ->add('search', TextType::class, ['required' => false, 'label' => 'Search']);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Volley\FaceBundle\Form\Model\Filter',
            'csrf_protection' => false
        ));
    }
}