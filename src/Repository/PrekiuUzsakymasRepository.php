<?php

namespace App\Repository;

use App\Entity\PrekiuUzsakymas;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PrekiuUzsakymas|null find($id, $lockMode = null, $lockVersion = null)
 * @method PrekiuUzsakymas|null findOneBy(array $criteria, array $orderBy = null)
 * @method PrekiuUzsakymas[]    findAll()
 * @method PrekiuUzsakymas[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PrekiuUzsakymasRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PrekiuUzsakymas::class);
    }

    // /**
    //  * @return PrekiuUzsakymas[] Returns an array of PrekiuUzsakymas objects
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
    public function findOneBySomeField($value): ?PrekiuUzsakymas
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
