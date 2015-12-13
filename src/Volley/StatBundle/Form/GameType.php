<?php

namespace Volley\StatBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Volley\StatBundle\Entity\Game;
use Volley\StatBundle\Entity\TeamRepository;

class GameType extends AbstractType
{
    /**
     * @var Game
     */
    private $game;

    /**
     * @param Game $game
     *
     * GameType constructor.
     */
    public function __construct(Game $game)
    {
        $this->game = $game;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $game = $this->game;
        $builder
            ->add('number')
            ->add('season')
            ->add('tour')
            ->add('homeTeam','entity',[
                'class' => 'Volley\StatBundle\Entity\Team',
                'query_builder' =>  function (TeamRepository $repository) use ($game) {
                    $query = $repository->createQueryBuilder('t')
                        ->addOrderBy('t.id', 'ASC');
                    if ($game->getSeason()) {
                        $query
                            ->leftJoin('t.seasons', 'season')
                            ->andWhere('season = ?1')
                            ->setParameter(1, $game->getSeason()->getId());
                    }
                    return $query;
                }
            ])
            ->add('awayTeam','entity',[
                'class' => 'Volley\StatBundle\Entity\Team',
                'query_builder' =>  function (TeamRepository $repository) use ($game) {
                    $query = $repository->createQueryBuilder('t')
                        ->addOrderBy('t.id', 'ASC');
                    if ($game->getSeason()) {
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
            ->add('date')
            ->add('sets', 'collection', array('type' => new GameSetType(), 'allow_add' => true, 'allow_delete' => true, 'by_reference' => false, 'label' => false))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Volley\StatBundle\Entity\Game'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'volley_statbundle_game';
    }
}
