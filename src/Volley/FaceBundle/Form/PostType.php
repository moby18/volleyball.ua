<?php

namespace Volley\FaceBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PostType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
//            ->add('slug')
            ->add('content', 'textarea', [
                'label' => 'Short content'
            ])
            ->add('text', 'textarea', [
                'label' => 'Full text',
                'attr' => array(
                    'class' => 'tinymce',
                    'data-theme' => 'advanced' // simple, advanced, bbcode
                ), 'required'=>false
            ])
            ->add('state')
//            ->add('created')
            ->add('published', 'datetime', [
                'widget' => 'single_text',
//                'widget' => 'choice',
//                'format' => 'YYYY-MM-dd hh:mm:ss',
                'required' => true])
//            ->add('content')
            ->add('createdBy')
            ->add('modifiedBy')
            ->add('source')
            ->add('ordering')
            ->add('metakey')
            ->add('metadescr')
            ->add('hits')
//            ->add('metadata')
            ->add('featured')
            ->add('language')
            ->add('category','entity', [
                'class' => 'Volley\FaceBundle\Entity\Category',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->select('c.id, c.name')
                        ->orderBy('c.lft', 'ASC');
                }
            ])
            ->add('file', null, [
                'label' => 'Post Image (width>=555px and height>=350px)'
            ])
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Volley\FaceBundle\Entity\Post'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'volley_bundle_facebundle_post';
    }
}
