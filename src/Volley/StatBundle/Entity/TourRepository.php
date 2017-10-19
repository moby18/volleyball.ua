<?php

namespace Volley\StatBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Volley\StatBundle\Form\Model\GameFilter;

class TourRepository extends EntityRepository
{
    /**
     * Get tours by rounds
     *
     * @param array $rounds
     * @return array
     */
    public function findByRounds(array $rounds)
    {
        $ids = [];
        foreach ($rounds as $round) {
            $ids[] = $round->getId();
        }
        return $this->createQueryBuilder('t')
            ->andWhere('t.round in (' . implode(',', $ids) . ')')
            ->orderBy('t.id', 'ASC')
            ->getQuery()->getResult();
    }

    function findByFilter(GameFilter $filter)
    {
        $query = $this->createQueryBuilder('t')
            ->innerJoin('t.season', 'season', Join::WITH, 'season.id = t.season')
            ->innerJoin('season.tournament', 'tournament', Join::WITH, 'tournament.id = season.tournament')
            ->innerJoin('t.round', 'round', Join::WITH, 'round.id = t.round');
        if ($filter->getCountry()) {
            $query->innerJoin('tournament.country', 'country', Join::WITH, 'country.id = tournament.country')
                ->andWhere('country.id = ?1')
                ->setParameter(1, $filter->getCountry());
        }
        if ($filter->getTournament()) {
            $query->andWhere('tournament.id = ?2')
                ->setParameter(2, $filter->getTournament());
        }
        if ($filter->getSeason()) {
            $query->andWhere('season.id = ?3')
                ->setParameter(3, $filter->getSeason());
        }
        if ($filter->getRound()) {
            $query->andWhere('round.id = ?4')
                ->setParameter(4, $filter->getRound());
        }
        return $query->addOrderBy('t.season', 'ASC')
            ->addOrderBy('t.round', 'ASC')
            ->addOrderBy('t.id', 'ASC')
            ->getQuery();
    }
}
