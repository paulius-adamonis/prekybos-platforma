<?php

namespace App\Repository;

use App\Entity\TurgPrekesKategorija;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method TurgPrekesKategorija|null find($id, $lockMode = null, $lockVersion = null)
 * @method TurgPrekesKategorija|null findOneBy(array $criteria, array $orderBy = null)
 * @method TurgPrekesKategorija[]    findAll()
 * @method TurgPrekesKategorija[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TurgPrekesKategorijaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TurgPrekesKategorija::class);
    }

    // /**
    //  * @return TurgPrekesKategorija[] Returns an array of TurgPrekesKategorija objects
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
    public function findOneBySomeField($value): ?TurgPrekesKategorija
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
