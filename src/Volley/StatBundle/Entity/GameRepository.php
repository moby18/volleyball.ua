<?php

namespace Volley\StatBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\Query\Expr\Join;
use Volley\StatBundle\Form\Model\GameFilter;

class GameRepository extends EntityRepository
{
    function findByFilter(GameFilter $filter)
    {
        $query = $this->createQueryBuilder('g')
            ->innerJoin('g.season', 'season', Join::WITH, 'season.id = g.season')
            ->innerJoin('season.tournament', 'tournament', Join::WITH, 'tournament.id = season.tournament')
            ->innerJoin('tournament.country', 'country', Join::WITH, 'country.id = tournament.country')
            ->innerJoin('g.tour', 'tour', Join::WITH, 'tour.id = g.tour')
            ->innerJoin('tour.round', 'round', Join::WITH, 'round.id = tour.round')
            //->innerJoin('g.homeTeam','homeTeam',Join::WITH,'homeTeam.id = g.homeTeam')
            //->innerJoin('g.awayTeam','awayTeam',Join::WITH,'awayTeam.id = g.awayTeam')
            ->orderBy('g.id', 'ASC');
        if ($filter->getTeam()) {
            $query->andWhere($query->expr()->orX(
                $query->expr()->eq('g.homeTeam', '?1'),
                $query->expr()->eq('g.awayTeam', '?1')
            )
            )->setParameter(1, $filter->getTeam());
        }
        if ($filter->getCountry()) {
            $query->andWhere('country.id = ?2')
                ->setParameter(2, $filter->getCountry());
        }
        if ($filter->getTournament()) {
            $query->andWhere('tournament.id = ?3')
                ->setParameter(3, $filter->getTournament());
        }
        if ($filter->getSeason()) {
            $query->andWhere('season.id = ?4')
                ->setParameter(4, $filter->getSeason());
        }
        if ($filter->getRound()) {
            $query->andWhere('round.id = ?5')
                ->setParameter(5, $filter->getRound());
        }
        if ($filter->getTour()) {
            $query->andWhere('tour.id = ?6')
                ->setParameter(6, $filter->getTour());
        }
        return $query->getQuery()->getResult();

    }
}
