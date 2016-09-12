<?php

namespace Volley\FaceBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Volley\FaceBundle\Entity\Post;

class PostType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('image', 'comur_image', array(
                'uploadConfig' => array(
                    'uploadRoute' => 'comur_api_upload',        //optional
                    'uploadUrl' => Post::getUploadRootDir(),       // required - see explanation below (you can also put just a dir path)
                    'webDir' => Post::getUploadDir(),              // required - see explanation below (you can also put just a dir path)
                    'fileExt' => '*.jpg;*.gif;*.png;*.jpeg',    //optional
                    'libraryDir' => null,                       //optional
                    'libraryRoute' => 'comur_api_image_library', //optional
                    'showLibrary' => true,                      //optional
                    'saveOriginal' => 'originalImage',          //optional
                    'generateFilename' => true          //optional
                ),
                'cropConfig' => array(
                    'minWidth' => 588,
                    'minHeight' => 300,
                    'aspectRatio' => true,              //optional
                    'cropRoute' => 'comur_api_crop',    //optional
                    'forceResize' => false,             //optional
                    'thumbs' => array(                  //optional
                        array(
                            'maxWidth' => 180,
                            'maxHeight' => 400,
                            'useAsFieldImage' => true  //optional
                        )
                    )
                )
            ))
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
            ->add('images',null,[
                'label' => false,
                'attr' => ['data-toggle' => 'toggle', 'data-on' => 'Images', 'data-off' => 'No Images', 'data-onstyle' => 'info']
            ])
            ->add('videos',null,[
                'label' => false,
                'attr' => ['data-toggle' => 'toggle', 'data-on' => 'Videos', 'data-off' => 'No Videos', 'data-onstyle' => 'info']
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
