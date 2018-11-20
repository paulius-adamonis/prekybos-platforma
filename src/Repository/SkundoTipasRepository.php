<?php

namespace App\Repository;

use App\Entity\SkundoTipas;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method SkundoTipas|null find($id, $lockMode = null, $lockVersion = null)
 * @method SkundoTipas|null findOneBy(array $criteria, array $orderBy = null)
 * @method SkundoTipas[]    findAll()
 * @method SkundoTipas[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SkundoTipasRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, SkundoTipas::class);
    }

    // /**
    //  * @return SkundoTipas[] Returns an array of SkundoTipas objects
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
    public function findOneBySomeField($value): ?SkundoTipas
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
