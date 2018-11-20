<?php

namespace App\Repository;

use App\Entity\PrekiuPriklausymas;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PrekiuPriklausymas|null find($id, $lockMode = null, $lockVersion = null)
 * @method PrekiuPriklausymas|null findOneBy(array $criteria, array $orderBy = null)
 * @method PrekiuPriklausymas[]    findAll()
 * @method PrekiuPriklausymas[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PrekiuPriklausymasRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PrekiuPriklausymas::class);
    }

    // /**
    //  * @return PrekiuPriklausymas[] Returns an array of PrekiuPriklausymas objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PrekiuPriklausymas
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
