<?php

namespace App\Repository;

use App\Entity\Kokybe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Kokybe|null find($id, $lockMode = null, $lockVersion = null)
 * @method Kokybe|null findOneBy(array $criteria, array $orderBy = null)
 * @method Kokybe[]    findAll()
 * @method Kokybe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class KokybeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Kokybe::class);
    }

    // /**
    //  * @return Kokybe[] Returns an array of Kokybe objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('k')
            ->andWhere('k.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('k.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Kokybe
    {
        return $this->createQueryBuilder('k')
            ->andWhere('k.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
