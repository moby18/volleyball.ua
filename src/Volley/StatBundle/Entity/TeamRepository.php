<?php

namespace Volley\StatBundle\Entity;

use Doctrine\ORM\EntityRepository;

class TeamRepository extends EntityRepository
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

    /**
     * * Get team by query
     *
     * @param $q
     * @return array
     */
    public function findByName($q)
    {
        $qb = $this->createQueryBuilder('t');
        return $qb->select('t')
            ->andWhere($qb->expr()->orX(
                $qb->expr()->like('t.name', $qb->expr()->literal('%' . $q . '%'))
            ))
            ->orderBy('t.name', 'ASC')
            ->setFirstResult(0)
            ->setMaxResults(10)
            ->getQuery()->getResult();

    }
}
