<?php

namespace App\Repository;

use App\Entity\TurgausPreke;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method TurgausPreke|null find($id, $lockMode = null, $lockVersion = null)
 * @method TurgausPreke|null findOneBy(array $criteria, array $orderBy = null)
 * @method TurgausPreke[]    findAll()
 * @method TurgausPreke[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TurgausPrekeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TurgausPreke::class);
    }

    // /**
    //  * @return TurgausPreke[] Returns an array of TurgausPreke objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TurgausPreke
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
