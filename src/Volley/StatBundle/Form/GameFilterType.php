<?php

namespace Volley\StatBundle\Form;

use Doctrine\ORM\Query\Expr\Join;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
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
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var GameFilter $gameFilter */
        $gameFilter = $options['gameFilter'];
        $builder
            ->add('country', EntityType::class, [
                'class' => 'Volley\StatBundle\Entity\Country',
                'label' => false,
                'placeholder' => ' - Country - ',
                'empty_data' => null,
                'required' => false,
                'attr'=>["onchange"=>"this.form.submit()"]
            ])
            ->add('tournament', EntityType::class, [
                'class' => 'Volley\StatBundle\Entity\Tournament',
                'label' => false,
                'placeholder' => ' - Tournament - ',
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
                },
                'attr'=>["onchange"=>"this.form.submit()"]
            ])
            ->add('season', EntityType::class, [
                'class' => 'Volley\StatBundle\Entity\Season',
                'label' => false,
                'placeholder' => ' - Season - ',
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
                },
                'attr'=>["onchange"=>"this.form.submit()"]
            ])
            ->add('round', EntityType::class, [
                'class' => 'Volley\StatBundle\Entity\Round',
                'label' => false,
                'placeholder' => ' - Round - ',
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
                },
                'attr'=>["onchange"=>"this.form.submit()"]
            ])
            ->add('tour', EntityType::class, [
                'class' => 'Volley\StatBundle\Entity\Tour',
                'label' => false,
                'placeholder' => ' - Tour - ',
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
                },
                'attr'=>["onchange"=>"this.form.submit()"]
            ])
            ->add('team', EntityType::class, [
                'class' => 'Volley\StatBundle\Entity\Team',
                'label' => false,
                'placeholder' => ' - Team - ',
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
                },
                'attr'=>["onchange"=>"this.form.submit()"]
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
        $resolver->setRequired('gameFilter');
    }
}
