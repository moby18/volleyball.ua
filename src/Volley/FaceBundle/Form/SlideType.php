<?php

namespace Volley\FaceBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Volley\FaceBundle\Entity\Slide;

class SlideType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('type','choice',[
                'choices' => [
                    Slide::TYPE_POST => 'Post',
                    Slide::TYPE_LINK => 'Link'
                ],
                'expanded' => true,
                'multiple' => false
            ])
            ->add('post',null,[
                'empty_value' => '',
                'required' => false
            ])
            ->add('link',null,[
                'required' => false
            ])
            ->add('ordering')
            ->add('status')
            ->add('file')
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Volley\FaceBundle\Entity\Slide',
            'validation_groups' => function (FormInterface $form) {
                $data = $form->getData();
                if (Slide::TYPE_POST == $data->getType()) {
                    return array('Default', 'posts');
                }
                if (Slide::TYPE_LINK == $data->getType()) {
                    return array('Default', 'links');
                }
                return array('Default');
            },
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'volley_bundle_facebundle_slide';
    }
}
