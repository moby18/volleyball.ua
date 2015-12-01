<?php

namespace Volley\StatBundle\Entity;

use Doctrine\ORM\EntityRepository;

class RoundRepository extends EntityRepository
{
    /**
     * Get rounds by seasons
     *
     * @param array $seasons
     * @return array
     */
    public function findBySeasons(array $seasons) {
        $ids = [];
        foreach ($seasons as $season) {
            $ids[] = $season->getId();
        }
        return $this->createQueryBuilder('r')
            ->andWhere('r.season in ('.implode(',',$ids).')')
            ->orderBy('r.id','ASC')
            ->getQuery()->getArrayResult();
    }
}
