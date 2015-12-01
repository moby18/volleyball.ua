<?php

namespace Volley\StatBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Volley\StatBundle\Form\Model\GameFilter;

class GameRepository extends EntityRepository
{
    function findByFilter(GameFilter $filter)
    {
        $query = $this->createQueryBuilder('g')
            ->orderBy('g.id', 'ASC');
        if ($filter->getTeam()) {
            $query->andWhere($query->expr()->orX(
                $query->expr()->eq('g.homeTeam', '?1'),
                $query->expr()->eq('g.awayTeam', '?1')
            )
            )->setParameter(1, $filter->getTeam());
        }
        if ($filter->getCountry()) {
//            $query
//                ->leftJoin('g.season', 'season', Join::WITH, 'season.co = ?1')
//                ->setParameter(1, $request['country'])
//                ->andWhere('s.tournament = tournament.id');
        } elseif ($filter->getRound()) {
//            $query
//                ->leftJoin('g.season', 'season', Join::WITH, 'g.country = ?1')
//                ->setParameter(1, $request['country'])
//                ->andWhere('s.tournament = tournament.id');
        } elseif ($filter->getTour()) {
        $query->andWhere(
            $query->expr()->eq('g.tour', '?2')
        )->setParameter(2, $filter->getTour());
    }

    }
}
