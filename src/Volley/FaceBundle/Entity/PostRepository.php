<?php

namespace Volley\FaceBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * PostRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PostRepository extends EntityRepository
{
    public function findAll()
    {
        return $this->findBy(array(), array('published' => 'DESC', 'id' => 'DESC'));
    }

    public function findByCategory(Category $category, $count = 5, $offset = 0)
    {
        return $this->findByCategoryQuery($category, $count = 5, $offset = 0)
            ->getResult();
    }

    public function findByCategoryQuery(Category $category, $count = 5, $offset = 0)
    {
        return $this->createQueryBuilder('p')
            ->select('p')
            ->innerJoin('p.category', 'category')
            ->andWhere('category.lft >= :lft AND category.rgt <= :rgt')
            ->setParameter('lft', $category->getLft())
            ->setParameter('rgt', $category->getRgt())
            ->andWhere('p.state > 0')
            ->andWhere('p.published <= :date')
            ->setParameter('date', new \DateTime())
            ->setMaxResults($count)
            ->setFirstResult($offset)
            ->orderBy('p.featured', 'DESC')
            ->addOrderBy('p.published', 'DESC')
            ->getQuery();
    }

    public function findPopularByCategory(Category $category, $count = 5, $offset = 0)
    {
        $today = new \DateTime();
        return $this->createQueryBuilder('p')
            ->select('p')
            ->innerJoin('p.category', 'category')
            ->andWhere('category.lft >= :lft AND category.rgt <= :rgt')
            ->setParameter('lft', $category->getLft())
            ->setParameter('rgt', $category->getRgt())
            ->andWhere('p.state > 0')
            ->andWhere('p.published <= :date1')
            ->setParameter('date1', $today)
            ->andWhere('p.published >= :date2')
            ->setParameter('date2', $today->sub( new \DateInterval('P14D') ))
            ->setMaxResults($count)
            ->setFirstResult($offset)
            ->addOrderBy('p.hits', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findWithOptions($slug)
    {
        return $this->createQueryBuilder('p')
            ->select('p')
            ->andWhere('p.slug = :slug')
            ->setParameter('slug', $slug)
            ->andWhere('p.state > 0')
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function unsetFeatured($entity)
    {
        return $this->createQueryBuilder('p')
            ->update()
            ->set('p.featured', '?1')
            ->setParameter(1, 0)
            ->andWhere('p.id <> :id')
            ->setParameter('id', $entity->getId())
            ->getQuery()
            ->execute();
    }
}
