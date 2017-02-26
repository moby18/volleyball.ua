<?php

namespace Volley\StatBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Volley\StatBundle\Entity\SchoolPerson;

class SchoolPersonType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', null, [
                'label' => "Ім'я",
                'required' => true
            ])
            ->add('middleName', null, [
                'label' => "По батькові"
            ])
            ->add('lastName', null, [
                'label' => "Прізвище",
                'required' => true
            ])
            ->add('sex', ChoiceType::class, [
                'choices' => SchoolPerson::SEX,
                'expanded' => true,
                'multiple' => false,
                'label' => 'Стать',
            ])
//            ->add('slug')
//            ->add('country')
            ->add('birthDate', 'date', array(
                    'years' => range(1920, 2016),
                    'format' => 'dd-MM-yyyy',
                    'label' => 'Дата народження'
                )
            )
//            ->add('nationality', null, [
//                'label' => 'Country of birth'
//            ])
            ->add('file', null, [
                'label' => 'Фото'
            ])
//            ->add('type', ChoiceType::class, [
//                'choices' => SchoolPerson::TYPE
//            ])
//            ->add('title')
            ->add('level', null, [
                'label' => "Категорія"
            ])
            ->add('grade', null, [
                'label' => "Спотривне звання (якщо є)"
            ])
            ->add('schoolName', null, [
                'label' => "Назва ДЮСШ, школи або інша назва тренування"
            ])
            ->add('schoolAddress', null, [
                'label' => "Адреса ДЮСШ, школи, тренування (область, населений пункт, вулиця, номер будівлі...)"
            ])
            ->add('whom', ChoiceType::class, [
                'choices' => SchoolPerson::SEX_YOUNG,
                'multiple' => true,
                'expanded' => true,
                'label' => 'Кого тренує'
            ])
            ->add('phone', null, [
                'label' => "Телефон (або декілька через кому)"
            ])
            ->add('email', null, [
                'label' => "Електронна пошта (або декілька через кому)"
            ])
            ->add('social', null, [
                'label' => "Сторінка в соц. мережах (або декілька через кому)"
            ])
            ->add('whoFilled', null, [
                'label' => "Іформація про того хто заповнює цю форму (ім'я, контактна інформація)"
            ])
//            ->add('height')
//            ->add('weight')
//            ->add('spike')
//            ->add('block')
            ->add('description', 'textarea', [
                'label' => 'Додаткова інформація (інша інформація у довільній формі, яка не підпала під описані вище поля, але якою важдило поділитися. Наприклад: розклад тренуваня, інформація про тренера, відомі вихованці, інше...)',
                'attr' => array(
                    'rows' => 20,
                    'class' => 'tinymce',
                    'data-theme' => 'advanced', // simple, advanced, bbcode
                ),
                'required' => false
            ]);
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Volley\StatBundle\Entity\SchoolPerson'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'volley_bundle_statbundle_person';
    }
}
