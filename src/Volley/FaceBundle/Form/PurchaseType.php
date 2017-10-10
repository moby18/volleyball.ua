<?php

namespace Volley\FaceBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PurchaseType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',null, [
                'label' => 'Имя',
                'required' => false
            ])
            ->add('phone', null, [
                'label' => 'Телефон',
                'required' => false
            ])
            ->add('email', null, [
                'label' => 'Email',
                'required' => false
            ])
            ->add('comment', TextareaType::class, [
                'label' => 'Комментарий',
                'required' => false
            ])
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Volley\FaceBundle\Entity\Purchase'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'volley_bundle_facebundle_purchase';
    }
}
