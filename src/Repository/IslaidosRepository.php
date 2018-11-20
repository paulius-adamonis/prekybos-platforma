<?php

namespace App\Repository;

use App\Entity\Islaidos;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Islaidos|null find($id, $lockMode = null, $lockVersion = null)
 * @method Islaidos|null findOneBy(array $criteria, array $orderBy = null)
 * @method Islaidos[]    findAll()
 * @method Islaidos[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IslaidosRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Islaidos::class);
    }

    // /**
    //  * @return Islaidos[] Returns an array of Islaidos objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Islaidos
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
