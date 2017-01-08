<?php

namespace Volley\StatBundle\Entity;

use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\ORM\EntityRepository;

class PersonRepository extends EntityRepository
{
    public function findByName($q)
    {
        $qb = $this->createQueryBuilder('p');
        return $qb->select('p')
            ->andWhere($qb->expr()->orX(
                $qb->expr()->like('p.firstName', $qb->expr()->literal('%' . $q . '%')),
                $qb->expr()->like('p.middleName', $qb->expr()->literal('%' . $q . '%')),
                $qb->expr()->like('p.lastName', $qb->expr()->literal('%' . $q . '%'))
            ))
            ->orderBy('p.firstName', 'ASC')
            ->setFirstResult(0)
            ->setMaxResults(10)
            ->getQuery()->getResult();

    }
}
