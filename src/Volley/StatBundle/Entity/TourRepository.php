<?php

namespace Volley\StatBundle\Entity;

use Doctrine\ORM\EntityRepository;

class TourRepository extends EntityRepository
{
    /**
     * Get tours by rounds
     *
     * @param array $rounds
     * @return array
     */
    public function findByRounds(array $rounds) {
        $ids = [];
        foreach ($rounds as $round) {
            $ids[] = $round->getId();
        }
        return $this->createQueryBuilder('t')
            ->andWhere('t.round in ('.implode(',',$ids).')')
            ->orderBy('t.id','ASC')
            ->getQuery()->getArrayResult();
    }
}
