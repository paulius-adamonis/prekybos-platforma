<?php

namespace App\Repository;

use App\Entity\SandeliuPriklausymas;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method SandeliuPriklausymas|null find($id, $lockMode = null, $lockVersion = null)
 * @method SandeliuPriklausymas|null findOneBy(array $criteria, array $orderBy = null)
 * @method SandeliuPriklausymas[]    findAll()
 * @method SandeliuPriklausymas[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SandeliuPriklausymasRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, SandeliuPriklausymas::class);
    }

    // /**
    //  * @return SandeliuPriklausymas[] Returns an array of SandeliuPriklausymas objects
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
    public function findOneBySomeField($value): ?SandeliuPriklausymas
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
