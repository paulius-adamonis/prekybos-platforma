<?php

namespace App\Repository;

use App\Entity\Busena;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Busena|null find($id, $lockMode = null, $lockVersion = null)
 * @method Busena|null findOneBy(array $criteria, array $orderBy = null)
 * @method Busena[]    findAll()
 * @method Busena[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BusenaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Busena::class);
    }

    // /**
    //  * @return Busena[] Returns an array of Busena objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Busena
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
