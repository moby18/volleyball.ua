<?php

namespace Volley\StatBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Volley\StatBundle\Entity\Game;
use Volley\StatBundle\Entity\TeamRepository;

class GameType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var Game $game */
        $game = $options['game'];
        $builder
            ->add('number')
            ->add('season')
            ->add('round')
            ->add('tour')
            ->add('homeTeam',EntityType::class,[
                'class' => 'Volley\StatBundle\Entity\Team',
                'query_builder' =>  function (TeamRepository $repository) use ($game) {
                    $query = $repository->createQueryBuilder('t')
                        ->addOrderBy('t.id', 'ASC');
                    if ($game ? $game->getSeason() : false) {
                        $query
                            ->leftJoin('t.seasons', 'season')
                            ->andWhere('season = ?1')
                            ->setParameter(1, $game->getSeason()->getId());
                    }
                    return $query;
                }
            ])
            ->add('awayTeam',EntityType::class,[
                'class' => 'Volley\StatBundle\Entity\Team',
                'query_builder' =>  function (TeamRepository $repository) use ($game) {
                    $query = $repository->createQueryBuilder('t')
                        ->addOrderBy('t.id', 'ASC');
                    if ($game ? $game->getSeason() : false) {
                        $query
                            ->leftJoin('t.seasons', 'season')
                            ->andWhere('season = ?1')
                            ->setParameter(1, $game->getSeason()->getId());
                    }
                    return $query;
                }
            ])
//            ->add('name')
            ->add('duration')
            ->add('homeTeamEmpty')
            ->add('awayTeamEmpty')
            ->add('score','hidden',[])
            ->add('scoreSetHome')
            ->add('scoreSetAway')
            ->add('played')
            ->add('date', DateTimeType::class, [
                'widget' => 'single_text',
                'format' => 'YYYY-MM-dd HH:mm:ss',
                'required' => true])
            ->add('sets', 'collection', array('type' => new GameSetType(), 'allow_add' => true, 'allow_delete' => true, 'by_reference' => false, 'label' => false))
            ->add('links', 'collection', array('type' => new GameLinkType(), 'allow_add' => true, 'allow_delete' => true, 'by_reference' => false, 'label' => false))
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Volley\StatBundle\Entity\Game'
        ));
        $resolver->setRequired('game');
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'volley_statbundle_game';
    }
}
