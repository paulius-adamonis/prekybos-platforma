<?php

namespace App\Repository;

use App\Entity\PrekiuNarsymoIstorija;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PrekiuNarsymoIstorija|null find($id, $lockMode = null, $lockVersion = null)
 * @method PrekiuNarsymoIstorija|null findOneBy(array $criteria, array $orderBy = null)
 * @method PrekiuNarsymoIstorija[]    findAll()
 * @method PrekiuNarsymoIstorija[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PrekiuNarsymoIstorijaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PrekiuNarsymoIstorija::class);
    }

    // /**
    //  * @return PrekiuNarsymoIstorija[] Returns an array of PrekiuNarsymoIstorija objects
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
    public function findOneBySomeField($value): ?PrekiuNarsymoIstorija
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
