<?php

namespace CoeurBundle\Repository;

/**
 * RessourcesRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RessourcesRepository extends \Doctrine\ORM\EntityRepository
{
    public function getAllItems($user)
    {
        $qb = $this->createQueryBuilder('r');

        $qb
            ->join('r.utilisateurss', 'u')
            ->addSelect('u')
        ;

        $qb
            ->join('r.items', 'i')
            ->addSelect('i');

        $qb->andWhere('u.id = r.utilisateurss')->andWhere('u.id = :User')->setParameter(':User', $user);

        return $qb
            ->getQuery()
            ->getResult()
            ;
    }
}