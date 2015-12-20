<?php

namespace Volley\StatBundle\Entity;

use Doctrine\ORM\EntityRepository;

class SeasonRepository extends EntityRepository
{
    /**
     * Get seasons by tournaments
     *
     * @param array $tournaments
     * @return array
     */
    public function findByTournaments(array $tournaments) {
        $ids = [];
        foreach ($tournaments as $tournament) {
            $ids[] = $tournament->getId();
        }
        return $this->createQueryBuilder('s')
            ->andWhere('s.tournament in ('.implode(',',$ids).')')
            ->orderBy('s.id','ASC')
            ->getQuery()->getResult();
    }
}
