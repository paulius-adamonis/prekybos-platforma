<?php

namespace App\Repository;

use App\Entity\Zinute;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Zinute|null find($id, $lockMode = null, $lockVersion = null)
 * @method Zinute|null findOneBy(array $criteria, array $orderBy = null)
 * @method Zinute[]    findAll()
 * @method Zinute[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ZinuteRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Zinute::class);
    }

    // /**
    //  * @return Zinute[] Returns an array of Zinute objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('z')
            ->andWhere('z.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('z.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Zinute
    {
        return $this->createQueryBuilder('z')
            ->andWhere('z.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
