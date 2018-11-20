<?php

namespace App\Repository;

use App\Entity\Sandelis;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Sandelis|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sandelis|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sandelis[]    findAll()
 * @method Sandelis[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SandelisRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Sandelis::class);
    }

    // /**
    //  * @return Sandelis[] Returns an array of Sandelis objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Sandelis
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
