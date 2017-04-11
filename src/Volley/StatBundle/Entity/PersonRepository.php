<?php

namespace Volley\StatBundle\Entity;

use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\ORM\EntityRepository;
use DoctrineExtensions\Query\Mysql\Month;
use DoctrineExtensions\Query\Mysql\Day;

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

    function findByBirthdayDate(\DateTime $date = null)
    {
        $emConfig = $this->getEntityManager()->getConfiguration();
        $emConfig->addCustomDatetimeFunction('MONTH', 'DoctrineExtensions\Query\Mysql\Month');
        $emConfig->addCustomDatetimeFunction('DAY', 'DoctrineExtensions\Query\Mysql\Day');

        $today = $date ? $date : new \DateTime();
        $qb = $this->createQueryBuilder('p');
        $query = $qb
            ->andWhere('DAY(p.birthDate) = :day')
            ->setParameter('day', $today->format('d'))
            ->andWhere('MONTH(p.birthDate) = :month')
            ->setParameter('month', $today->format('m'))
            ->addOrderBy('p.firstName', 'ASC')
            ->addOrderBy('p.middleName', 'ASC')
            ->addOrderBy('p.lastName', 'ASC');

        return $query->getQuery()->getResult();
    }
}
