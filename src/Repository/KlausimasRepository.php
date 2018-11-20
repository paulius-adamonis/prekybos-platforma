<?php

namespace App\Repository;

use App\Entity\Klausimas;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Klausimas|null find($id, $lockMode = null, $lockVersion = null)
 * @method Klausimas|null findOneBy(array $criteria, array $orderBy = null)
 * @method Klausimas[]    findAll()
 * @method Klausimas[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class KlausimasRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Klausimas::class);
    }

    // /**
    //  * @return Klausimas[] Returns an array of Klausimas objects
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
    public function findOneBySomeField($value): ?Klausimas
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
