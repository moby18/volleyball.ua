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
            ->add('state',null,[
                'label' => false,
                'attr' => ['data-toggle' => 'toggle', 'data-on' => 'Published', 'data-off' => 'Unpublished', 'data-onstyle' => 'success', 'data-offstyle'=> 'danger']
            ])
//            ->add('created')
            ->add('published', 'datetime', [
                'widget' => 'single_text',
//                'widget' => 'choice',
                'format' => 'YYYY-MM-dd HH:mm:ss',
                'required' => true])
//            ->add('content')
            ->add('createdBy')
            ->add('modifiedBy')
            ->add('sourceName')
            ->add('sourceLink')
            ->add('ordering')
            ->add('metakey')
            ->add('metadescr')
            ->add('hits')
//            ->add('metadata')
            ->add('featured',null,[
                'label' => false,
                'attr' => ['data-toggle' => 'toggle', 'data-on' => 'Featured', 'data-off' => 'Not Featured', 'data-onstyle' => 'info']
            ])
            ->add('recommended',null,[
                'label' => false,
                'attr' => ['data-toggle' => 'toggle', 'data-on' => 'Recommended', 'data-off' => 'Not Recommended', 'data-onstyle' => 'info']
            ])
            ->add('images',null,[
                'label' => false,
                'attr' => ['data-toggle' => 'toggle', 'data-on' => 'Images', 'data-off' => 'No Images', 'data-onstyle' => 'info']
            ])
            ->add('videos',null,[
                'label' => false,
                'attr' => ['data-toggle' => 'toggle', 'data-on' => 'Videos', 'data-off' => 'No Videos', 'data-onstyle' => 'info']
            ])
            ->add('translated',null,[
                'label' => false,
                'attr' => ['data-toggle' => 'toggle', 'data-on' => 'Translated', 'data-off' => "Not translated", 'data-onstyle' => 'info']
            ])
            ->add('language')
            ->add('category','entity', [
                'class' => 'Volley\FaceBundle\Entity\Category',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
//                        ->select('c.id, c.name')
                        ->orderBy('c.lft', 'ASC');
                }
            ])
            ->add('file', null, [
                'label' => 'Post Image (width>=760 and height>=400px)'
            ])
            ->add('imageDescr', null, [
                'label' => 'Post Image Description'
            ])
            ->add('imageSource', null, [
                'label' => 'Post Image Source'
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
