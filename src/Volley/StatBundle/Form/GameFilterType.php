<?php

namespace Volley\StatBundle\Form;

use Assetic\Asset\BaseAsset;
use Doctrine\ORM\Query\Expr\Join;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Volley\StatBundle\Entity\RoundRepository;
use Volley\StatBundle\Entity\SeasonRepository;
use Volley\StatBundle\Entity\TeamRepository;
use Volley\StatBundle\Entity\TournamentRepository;
use Volley\StatBundle\Entity\TourRepository;
use Volley\StatBundle\Form\Model\GameFilter;

class GameFilterType extends AbstractType
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @var GameFilter
     */
    private $gameFilter;

    /**
     * GameFilterType constructor.
     *
     * @param GameFilter $gameFilter
     */
    public function __construct(GameFilter $gameFilter)
    {
        $this->gameFilter = $gameFilter;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $gameFilter = $this->gameFilter;
        $builder
            ->add('country', 'entity', [
                'class' => 'Volley\StatBundle\Entity\Country',
                'label' => false,
                'empty_value' => ' - Country - ',
                'empty_data' => null,
                'required' => false
            ])
            ->add('tournament', 'entity', [
                'class' => 'Volley\StatBundle\Entity\Tournament',
                'label' => false,
                'empty_value' => ' - Tournament - ',
                'empty_data' => null,
                'required' => false,
                'query_builder' => function (TournamentRepository $repository) use ($gameFilter) {
                    $query = $repository->createQueryBuilder('t')
                        ->add('orderBy', 't.id ASC');
                    if ($gameFilter->getCountry()) {
                        $query
                            ->andWhere('t.country = ?1')
                            ->setParameter(1, $gameFilter->getCountry()->getId());
                    }
                    return $query;
                }
            ])
            ->add('season', 'entity', [
                'class' => 'Volley\StatBundle\Entity\Season',
                'label' => false,
                'empty_value' => ' - Season - ',
                'empty_data' => null,
                'required' => false,
                'query_builder' => function (SeasonRepository $repository) use ($gameFilter) {
                    $query = $repository->createQueryBuilder('s')
                        ->add('orderBy', 's.id ASC');
                    if ($gameFilter->getTournament()) {
                        $query
                            ->andWhere('s.tournament = ?1')
                            ->setParameter(1, $gameFilter->getTournament()->getId());
                    } elseif ($gameFilter->getCountry()) {
                        $query
                            ->leftJoin('s.tournament', 'tournament', Join::WITH, 'tournament.country = ?1')
                            ->setParameter(1, $gameFilter->getCountry()->getId())
                            ->andWhere('s.tournament = tournament.id');
                    }
                    return $query;
                }
            ])
            ->add('round', 'entity', [
                'class' => 'Volley\StatBundle\Entity\Round',
                'label' => false,
                'empty_value' => ' - Round - ',
                'empty_data' => null,
                'required' => false,
                'query_builder' => function (RoundRepository $repository) use ($gameFilter) {
                    $query = $repository->createQueryBuilder('r')
                        ->add('orderBy', 'r.id ASC');
                    if ($gameFilter->getSeason()) {
                        $query
                            ->andWhere('r.season = ?1')
                            ->setParameter(1, $gameFilter->getSeason()->getId());
                    } elseif ($gameFilter->getTournament()) {
                        $query
                            ->leftJoin('r.season', 'season', Join::WITH, 'season.tournament = ?1')
                            ->setParameter(1, $gameFilter->getTournament()->getId())
                            ->andWhere('r.season = season.id');
                    }
                    return $query;
                }
            ])
            ->add('tour', 'entity', [
                'class' => 'Volley\StatBundle\Entity\Tour',
                'label' => false,
                'empty_value' => ' - Tour - ',
                'empty_data' => null,
                'required' => false,
                'query_builder' => function (TourRepository $repository) use ($gameFilter) {
                    $query = $repository->createQueryBuilder('t')
                        ->add('orderBy', 't.id ASC');
                    if ($gameFilter->getRound()) {
                        $query
                            ->andWhere('t.round = ?1')
                            ->setParameter(1, $gameFilter->getRound()->getId());
                    } elseif ($gameFilter->getSeason()) {
                        $query
                            ->leftJoin('t.round', 'round', Join::WITH, 'round.season = ?1')
                            ->setParameter(1, $gameFilter->getSeason()->getId())
                            ->andWhere('t.round = round.id');
                    } elseif ($gameFilter->getTournament()) {
                        $query
                            ->leftJoin('t.season', 'season', Join::WITH, 'season.tournament = ?1')
                            ->setParameter(1, $gameFilter->getTournament()->getId())
                            ->andWhere('t.season = season.id');
                    }
                    return $query;
                }
            ])
            ->add('team', 'entity', [
                'class' => 'Volley\StatBundle\Entity\Team',
                'label' => false,
                'empty_value' => ' - Team - ',
                'empty_data' => null,
                'required' => false,
                'query_builder' => function (TeamRepository $repository) use ($gameFilter) {
                    $query = $repository->createQueryBuilder('t')
                        ->add('orderBy', 't.id ASC');
                    if ($gameFilter->getSeason()) {
                        $query
                            ->leftJoin('t.seasons', 'season')
                            ->andWhere('season = ?1')
                            ->setParameter(1, $gameFilter->getSeason()->getId());
                    } elseif ($gameFilter->getTournament()) {
                        $query
                            ->leftJoin('t.seasons', 'season')
                            ->leftJoin('season.tournament', 'tournament',Join::WITH, 'tournament.id = season.tournament')
                            ->andWhere('tournament.id = ?2')
                            ->setParameter(2, $gameFilter->getTournament()->getId());
                    } elseif ($gameFilter->getCountry()) {
                        $query
                            ->leftJoin('t.seasons', 'season')
                            ->leftJoin('season.tournament', 'tournament',Join::WITH, 'tournament.id = season.tournament')
                            ->leftJoin('tournament.country', 'country',Join::WITH, 'country.id = tournament.country')
                            ->andWhere('country.id = ?3')
                            ->setParameter(3, $gameFilter->getCountry()->getId());
                    }
                    return $query;
                }
            ]);

    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Volley\StatBundle\Form\Model\GameFilter',
            'attr' => ['class' => 'form-inline'],
            'csrf_protection' => false
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'filter';
    }
}
