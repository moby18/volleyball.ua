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
                'choices' => [12 => "All", 0 => 'Unpublished', 1 => 'Published'],
                'label' => 'Filter',
                'required' => false,
                'multiple' => false,
                'expanded' => false,
                'empty_data' => false
            ])
            ->add('featured', ChoiceType::class, [
                'choices' => [12 => "All", 0 => 'None Featured', 1 => 'Featured'],
                'label' => 'Filter',
                'required' => false,
                'multiple' => false,
                'expanded' => false,
                'empty_data' => false
            ])
            ->add('recommended', ChoiceType::class, [
                'choices' => [12 => "All", 0 => 'None Recommended', 1 => 'Recommended'],
                'label' => 'Filter',
                'required' => false,
                'multiple' => false,
                'expanded' => false,
                'empty_data' => false
            ])
            ->add('vu', ChoiceType::class, [
                'choices' => [12 => "All", 0 => 'None VU', 1 => 'VU'],
                'label' => 'Filter',
                'required' => false,
                'multiple' => false,
                'expanded' => false,
                'empty_data' => false
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

    /**
     * @return string
     */
    public function getName()
    {
        return '';
    }
}