<?php

namespace App\Repository;

use App\Entity\Atsiliepimas;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Atsiliepimas|null find($id, $lockMode = null, $lockVersion = null)
 * @method Atsiliepimas|null findOneBy(array $criteria, array $orderBy = null)
 * @method Atsiliepimas[]    findAll()
 * @method Atsiliepimas[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AtsiliepimasRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Atsiliepimas::class);
    }

    // /**
    //  * @return Atsiliepimas[] Returns an array of Atsiliepimas objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Atsiliepimas
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
